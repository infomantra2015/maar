<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infomantra\Utility;

use Zend\Crypt\Password\Bcrypt;
use Infomantra\Constant\AppConstant;

class Password {

    protected $salt = 'A15CD9652D4F4A3FB61A2A422AEAFDF0DEA4E703';
    protected $method = 'sha1';

    public function __construct($method = null) {
        if (!is_null($method)) {
            $this->method = $method;
        }
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function create($password) {
        if ($this->method == 'md5') {
            return md5($this->salt . $password);
        } elseif ($this->method == 'sha1') {
            return sha1($this->salt . $password);
        } elseif ($this->method == 'bcrypt') {
            $bcrypt = new Bcrypt();
            $bcrypt->setCost(AppConstant::BCRYT_COST);
            return $bcrypt->create($password);
        }
    }

    public function verify($plainPassword, $encryptedPassword) {
        if ($this->method == 'md5') {
            return $encryptedPassword == md5($this->salt . $plainPassword);
        } elseif ($this->method == 'sha1') {
            return $encryptedPassword == sha1($this->salt . $plainPassword);
        } elseif ($this->method == 'bcrypt') {
            $bcrypt = new Bcrypt();
            $bcrypt->setCost(AppConstant::BCRYT_COST);
            return $bcrypt->verify($plainPassword, $encryptedPassword);
        }
    }

}
