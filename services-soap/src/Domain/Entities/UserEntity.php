<?php

namespace App\Domain\Entities;

class UserEntity {

  public string $id;

  public string $document;

  public string $name;

  public string $email;

  public int $phone;

  public function __construct($data)
  {
    $this->id = $data['id'];
    $this->document = $data['document'];
    $this->name = $data['name'];
    $this->email = $data['email'];
    $this->phone = $data['phone'];
  }

  public function getId() {
    return $this->id;
  }

  public function getDocument() {
    return $this->document;
  }

  public function getName() {
    return $this->name;
  }

  public function getEmail() {
    return $this->email;
  }

  public function getPhone() {
    return $this->phone;
  }

  static public function toObject($data){
    return new UserEntity($data);
  }
}

?>