<?php

namespace User\Form\Fieldset\Element;

class UploadPicureElements {

    private $userId;
    private $profileImage;
    private $isProfilePicSet;

    public function getUserId() {
        return $this->userId;
    }

    public function getProfileImage() {
        return $this->profileImage;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setProfileImage($profileImage) {
        $this->profileImage = $profileImage;
    }
    function getIsProfilePicSet() {
        return $this->isProfilePicSet;
    }

    function setIsProfilePicSet($isProfilePicSet) {
        $this->isProfilePicSet = $isProfilePicSet;
    }    
}
