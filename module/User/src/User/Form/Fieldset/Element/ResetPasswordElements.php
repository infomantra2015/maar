<?php

namespace User\Form\Fieldset\Element;

class ResetPasswordElements {

    private $currentPassword;    
    private $password;
    private $confirmPassword;
    private $resetPasswordToken;
    
    public function getCurrentPassword() {
        return $this->currentPassword;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getConfirmPassword() {
        return $this->confirmPassword;
    }

    public function setCurrentPassword($currenPassword) {
        $this->currentPassword = $currenPassword;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setConfirmPassword($confirmPassword) {
        $this->confirmPassword = $confirmPassword;
    }
    
    public function getResetPasswordToken() {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken($resetPasswordToken) {
        $this->resetPasswordToken = $resetPasswordToken;
    }


}
