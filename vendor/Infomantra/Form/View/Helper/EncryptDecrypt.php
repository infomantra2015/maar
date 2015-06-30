<?php

namespace Infomantra\Form\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Infomantra\Utility\Cryptography;

class EncryptDecrypt extends AbstractHelper {

    public function encrypt($text) {
        $cryptography = new Cryptography();
        return $cryptography->encrypt($text);
    }

    public function decrypt($text) {
        $cryptography = new Cryptography();
        return $cryptography->decrypt($text);
    }

}
