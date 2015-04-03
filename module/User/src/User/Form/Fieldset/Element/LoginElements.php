<?php

namespace User\Form\Fieldset\Element;

class LoginElements {

    private $email;
    private $password;

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPassword($password) {
        $this->password = $password;
    }

}
