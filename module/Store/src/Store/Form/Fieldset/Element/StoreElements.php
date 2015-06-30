<?php

namespace Store\Form\Fieldset\Element;

class StoreElements {

    private $storeId;
    private $storeName;
    private $cityId;
    private $stateId;
    private $countryId;
    private $categoryId;
    private $createdOn;
    private $storeDescription;
    private $storeAddress;
    private $storePic;
    private $status;

    public function getStoreAddress() {
        return $this->storeAddress;
    }

    public function setStoreAddress($storeAddress) {
        $this->storeAddress = $storeAddress;
    }

    public function getStoreDescription() {
        return $this->storeDescription;
    }

    public function getCityId() {
        return $this->cityId;
    }

    public function getStateId() {
        return $this->stateId;
    }

    public function getCountryId() {
        return $this->countryId;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStoreDescription($storeDescription) {
        $this->storeDescription = $storeDescription;
    }

    public function setCityId($cityId) {
        $this->cityId = $cityId;
    }

    public function setStateId($stateId) {
        $this->stateId = $stateId;
    }

    public function setCountryId($countryId) {
        $this->countryId = $countryId;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getStoreId() {
        return $this->storeId;
    }

    public function getStoreName() {
        return $this->storeName;
    }

    public function setStoreId($storeId) {
        $this->storeId = $storeId;
    }

    public function setStoreName($storeName) {
        $this->storeName = $storeName;
    }

    function getStorePic() {
        return $this->storePic;
    }

    function setStorePic($storePic) {
        $this->storePic = $storePic;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

}
