<?php

namespace App\Presentation\Controller;

use App\Domain\Dtos\CreateBuyDto;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Repository\BuyRepositoryImpl;
use App\Infrastructure\Repository\UserRepositoryImpl;
use App\Infrastructure\Repository\WalletRepositoryImpl;
use App\Presentation\Service\XmlResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mime\Email;


class BuyController extends AbstractController {

  private XmlResponseService $xmlResponseService;
  private BuyRepositoryImpl $buyRepository;
  private UserRepositoryImpl $userRepository;
  private WalletRepositoryImpl $walletRepository;
  private  MailerInterface $mailer;

  function __construct(
    XmlResponseService $xmlResponseService, 
    BuyRepositoryImpl $buyRepository,
    UserRepositoryImpl $userRepository,
    WalletRepositoryImpl $walletRepository,
    MailerInterface $mailer,
  ) {
    $this->xmlResponseService = $xmlResponseService;
    $this->buyRepository = $buyRepository;
    $this->userRepository = $userRepository;
    $this->mailer = $mailer;
    $this->walletRepository = $walletRepository;
  }

  #[Route('/buy/get-code', methods: 'POST')]
  public function buy_get_code(
    #[MapRequestPayload(
      acceptFormat: 'json',
      validationGroups: ['strict', 'read'],
      validationFailedStatusCode: Response::HTTP_BAD_REQUEST
    )] 
    CreateBuyDto $createBuyDto,
    ValidatorInterface $validator, 
  ): Response {

    $errors = $validator->validate($createBuyDto);

    if (count($errors) > 0) {
      $errorMessages = [];

      foreach ($errors as $error) {
          $errorMessages["error"] = $error->getMessage();
      }
      $response = $this->xmlResponseService->toSoapFault(400, $errorMessages["error"]);
      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      );
    }

    $document = $createBuyDto->document;
    $phone = $createBuyDto->phone;

    $user = $this->userRepository->get_by_document($document);
    
    if(!$user){
      $response = $this->xmlResponseService->toSoapFault(400, "the User Dont's Exist");
      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      );
    }

    if($user->phone != $phone){
      $response = $this->xmlResponseService->toSoapFault(400, "Incorrect user phone number");
      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      );
    }

    // Producto Ficticio creado para asociar a la compra
    $product = array(
      "id" => rand(1, 10),
      "name" => "Laptop",
      "price" => 99
    );

    $balance = $this->walletRepository->getBalance($user)->balance;

    if($balance < $product["price"]){

      $response = $this->xmlResponseService->toSoapFault(400, "Insufficient funds");
      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      );
    }

    $code = random_int(100000, 999999);

    $sessionId = bin2hex(random_bytes(16));

    $infoBuy = array(
      "amount" => $product["price"],
      "sessionId" => $sessionId,
      "code" => $code
    );

    $this->buyRepository->create($user, $infoBuy);
    
    $url = $_SERVER['FRONTEND_URL_ROUTE_CODE'] . $code;
    
    $email = (new Email())
        ->from('epaycobuy@example.com')
        ->to($user->email)
        ->replyTo('soliditydevpro@gmail.com') // Reply-To address
        ->subject('Verify the Code for Buy')
        ->html('<p>Click Here to verify your buy <a href='. $url .'>Click Here</a></p>');

    $this->mailer->send($email);

    $data = array(
      "data" => ["session_id" => $sessionId],
      "success" => true
    );

    $response = $this->xmlResponseService->toSoapResponse($data);

    return new Response(
      $response
    );
  }

  #[Route('/buy/confirm/{code}', methods: 'GET')]
  public function buy_confirm(
    string $code,
    Request $request, 
  ): Response {
    
    $authorizationHeader = $request->headers->get('Authorization');

    if ($authorizationHeader && preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
        $sessionId = $matches[1];
    } else {
        $sessionId = null;
    }

    if (!$sessionId){
      $response = $this->xmlResponseService->toSoapFault(400, "Session Id Not Provide");

      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      ); 
    }

    $buy = $this->buyRepository->getBySession($sessionId);

    if(!$buy) {
      $response = $this->xmlResponseService->toSoapFault(404, "Session Id Invalid or Not Exist");
      return new Response(
        $response,
        Response::HTTP_NOT_FOUND
      ); 
    }

    if($buy->code != $code) {
      $response = $this->xmlResponseService->toSoapFault(400, "Code Invalid");
      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      ); 
    }

    $balance = $this->walletRepository->getBalance($buy->user)->balance;

    if((int)$balance < (int)$buy->amount){

      $response = $this->xmlResponseService->toSoapFault(400, "insufficient funds");
      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      ); 
    }

    $this->buyRepository->update($sessionId, 'completed');
    $this->walletRepository->discountBalance($buy->user, $buy->amount);
    
    $data = array(
      "success" => true,
      "message" => "Buy Proccess Successfully"
    );

    $response = $this->xmlResponseService->toSoapResponse($data);

    return new Response(
      $response
    );
  }
}

?>