<?php

namespace User\Form\Fieldset\Element;

class UploadPicureElements {

    private $userId;
    private $profileImage;

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
}
