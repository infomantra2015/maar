<?php

namespace User\Form\Fieldset\Element;

class RegisterElements {

    private $userId;
    private $firstName;
    private $lastName;
    private $gender;
    private $email;
    private $password;
    private $confirmassword;
    private $createdOn;
    private $userRoleId;
    
    public function getUserId() {
        return $this->userId;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
    public function getConfirmassword() {
        return $this->confirmassword;
    }

    public function setConfirmassword($confirmassword) {
        $this->confirmassword = $confirmassword;
    }
    
    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }
    
    public function getUserRoleId() {
        return $this->userRoleId;
    }

    public function setUserRoleId($userRoleId) {
        $this->userRoleId = $userRoleId;
    }


}
