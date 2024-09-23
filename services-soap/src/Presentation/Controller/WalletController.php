<?php

namespace App\Presentation\Controller;

use App\Domain\Dtos\WalletBalanceDto;
use App\Domain\Dtos\WalletRechargeDto;
use App\Infrastructure\Repository\UserRepositoryImpl;
use App\Infrastructure\Repository\WalletRepositoryImpl;
use App\Presentation\Service\XmlResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WalletController extends AbstractController {

  private XmlResponseService $xmlResponseService;
  private WalletRepositoryImpl $walletRepository;
  private UserRepositoryImpl $userRepository;

  function __construct(
    XmlResponseService $xmlResponseService, 
    WalletRepositoryImpl $walletRepository, 
    UserRepositoryImpl $userRepository
  )
  {
    $this->xmlResponseService = $xmlResponseService;
    $this->walletRepository = $walletRepository;
    $this->userRepository = $userRepository;
  }

  #[Route('/wallet/balance', methods: 'GET')]
  public function get_balance(
    Request $request,
    #[MapQueryString(
      validationGroups: ['strict', 'read'],
      validationFailedStatusCode: Response::HTTP_BAD_REQUEST
    )] WalletBalanceDto $walletBalanceDto,
    ValidatorInterface $validator
  ): Response {

    $errors = $validator->validate($walletBalanceDto);

    if (count($errors) > 0) {
      $errorMessages = [];

      foreach ($errors as $error) {
          $errorMessages["error"] = $error->getMessage();
      }
      $response = $this->xmlResponseService->toSoapResponse($errorMessages);
      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      );
    }

    $document = $request->query->get("document");
    $phone = $request->query->get("phone");
    
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
    $balance = $this->walletRepository->getBalance($user);

    $data = array(
      "data" => $balance,
      "success" => true,
    );
    $response = $this->xmlResponseService->toSoapResponse($data);
    return new Response(
      $response
    );
  }

  #[Route('/wallet/recharge-wallet', methods: 'POST')]
  public function load_money( 
    #[MapRequestPayload(
      acceptFormat: 'json',
      validationGroups: ['strict', 'read'],
      validationFailedStatusCode: Response::HTTP_BAD_REQUEST
    )] WalletRechargeDto $walletRechargeDto,
    ValidatorInterface $validator
  ): Response {

    $errors = $validator->validate($walletRechargeDto);

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

    $document = $walletRechargeDto->document;
    $phone = $walletRechargeDto->phone;
    $amount = $walletRechargeDto->amount;

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
    
    $wallet = $this->walletRepository->rechargeBalance($user, $amount);

    $data = array(
      "data" => $wallet,
      "success" => true,
    );

    $response = $this->xmlResponseService->toSoapResponse($data);
    return new Response(
      $response
    );
  }
}

?>