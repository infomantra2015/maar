<?php

namespace Store\Form\Fieldset\Element;

class OfferElements {

    private $offerId;
    private $offerTitle;
    private $validFrom;
    private $validTo;
    private $createdOn;
    private $status;
    private $offerDescription;

    public function getOfferId() {
        return $this->offerId;
    }

    public function getOfferTitle() {
        return $this->offerTitle;
    }

    public function getValidFrom() {
        return $this->validFrom;
    }

    public function getValidTo() {
        return $this->validTo;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getOfferDescription() {
        return $this->offerDescription;
    }

    public function setOfferId($offerId) {
        $this->offerId = $offerId;
    }

    public function setOfferTitle($offerTitle) {
        $this->offerTitle = $offerTitle;
    }

    public function setValidFrom($validFrom) {
        $this->validFrom = $validFrom;
    }

    public function setValidTo($validTo) {
        $this->validTo = $validTo;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function setOfferDescription($offerDescription) {
        $this->offerDescription = $offerDescription;
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
