<?php

namespace User\Form\Fieldset\Element;

class ProfileElements {

    private $userId;
    private $firstName;
    private $lastName;
    private $dob;
    private $mobileNumber;
    private $gender;

    public function getUserId() {
        return $this->userId;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getDob() {
        return $this->dob;
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

    public function setDob($dob) {
        $this->dob = $dob;
    }
    
    public function getMobileNumber() {
        return $this->mobileNumber;
    }

    public function setMobileNumber($mobileNumber) {
        $this->mobileNumber = $mobileNumber;
    }
    
    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }


}
