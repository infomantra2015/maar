<?php

namespace User\Form\Fieldset\Element;

class AddressElements {

    private $userId;
    private $countryId;
    private $stateId;
    private $cityId;
    private $address;

    public function getUserId() {
        return $this->userId;
    }

    public function getCountryId() {
        return $this->countryId;
    }

    public function getStateId() {
        return $this->stateId;
    }

    public function getCityId() {
        return $this->cityId;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setCountryId($countryId) {
        $this->countryId = $countryId;
    }

    public function setStateId($stateId) {
        $this->stateId = $stateId;
    }

    public function setCityId($cityId) {
        $this->cityId = $cityId;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

}
