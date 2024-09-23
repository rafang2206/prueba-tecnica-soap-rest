<?php

namespace App\Presentation\Controller;

use App\Domain\Dtos\CreateUserDto;
use App\Infrastructure\Repository\UserRepositoryImpl;
use App\Infrastructure\Repository\WalletRepositoryImpl;
use App\Presentation\Service\XmlResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController {

  private XmlResponseService $xmlResponseService;
  private UserRepositoryImpl $userRepository;
  private WalletRepositoryImpl $walletRepository;

  function __construct(
    XmlResponseService $xmlResponseService, 
    UserRepositoryImpl $userRepository,
    WalletRepositoryImpl $walletRepository
  )
  {
    $this->xmlResponseService = $xmlResponseService;
    $this->userRepository = $userRepository;
    $this->walletRepository = $walletRepository;
  }

  #[Route('/user/register', methods: 'POST')]
  public function register(
    #[MapRequestPayload(
      acceptFormat: 'json',
      validationGroups: ['strict', 'read'],
      validationFailedStatusCode: Response::HTTP_BAD_REQUEST
    )] 
    CreateUserDto $createUserDto,
    ValidatorInterface $validator
  ): Response {

    $errors = $validator->validate($createUserDto);

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

    $user = $this->userRepository->get_by_document($createUserDto->document);

    if($user){
      $response = $this->xmlResponseService->toSoapFault(400, "the document is already registered");
      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      );
    }

    $user = $this->userRepository->get_by_email($createUserDto->email);

    if($user){
      $response = $this->xmlResponseService->toSoapFault(400, "the Email is already registered");
      return new Response(
        $response,
        Response::HTTP_BAD_REQUEST
      );
    }

    $userRegister = $this->userRepository->create($createUserDto);

    $this->walletRepository->createWallet($userRegister);

    $data = array("success" => true, "message" => "User Register Successfully");

    $response = $this->xmlResponseService->toSoapResponse($data);

    return new Response(
      $response
    );
  }
}

?>