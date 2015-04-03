<?php

namespace User\Form\Fieldset\Element;

class SocialElements {

    private $userId;
    private $facebookUrl;
    private $twitterUrl;
    private $linkedinUrl;
    private $googlePlusUrl;
    private $websiteUrl;

    public function getUserId() {
        return $this->userId;
    }

    public function getFacebookUrl() {
        return $this->facebookUrl;
    }

    public function getTwitterUrl() {
        return $this->twitterUrl;
    }

    public function getLinkedinUrl() {
        return $this->linkedinUrl;
    }

    public function getGooglePlusUrl() {
        return $this->googlePlusUrl;
    }

    public function getWebsiteUrl() {
        return $this->websiteUrl;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setFacebookUrl($facebookUrl) {
        $this->facebookUrl = $facebookUrl;
    }

    public function setTwitterUrl($twitterUrl) {
        $this->twitterUrl = $twitterUrl;
    }

    public function setLinkedinUrl($linkedinUrl) {
        $this->linkedinUrl = $linkedinUrl;
    }

    public function setGooglePlusUrl($googlePlusUrl) {
        $this->googlePlusUrl = $googlePlusUrl;
    }

    public function setWebsiteUrl($websiteUrl) {
        $this->websiteUrl = $websiteUrl;
    }

}
