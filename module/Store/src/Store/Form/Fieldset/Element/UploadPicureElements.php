<?php

namespace Store\Form\Fieldset\Element;

class UploadPicureElements {

    private $storeId;
    private $userId;
    private $storeImage;
    private $isStoreLogo;

    function getStoreId() {
        return $this->storeId;
    }

    function getStoreImage() {
        return $this->storeImage;
    }

    function getUserId() {
        return $this->userId;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function getIsStoreLogoSet() {
        return $this->isStoreLogo;
    }

    function setStoreId($storeId) {
        $this->storeId = $storeId;
    }

    function setStoreImage($storeImage) {
        $this->storeImage = $storeImage;
    }

    function setIsStoreLogoSet($isStoreLogo) {
        $this->isProfilePicSet = $isStoreLogo;
    }
}
