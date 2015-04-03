<?php

namespace Infomantra\Utility;

use Zend\Crypt\BlockCipher;

class Cryptography {

    private $cipher = null;
    private $encryptionKey = 'ma&s@%#SD^5ar63';

    public function getCipher() {
        return $this->cipher;
    }

    public function getEncryptionKey() {
        return $this->encryptionKey;
    }

    public function init() {
        
        $this->cipher = BlockCipher::factory('mcrypt',
                        array('algorithm' => 'aes')
        );

        $this->getCipher()->setKey($this->getEncryptionKey());
    }

    public function encrypt($text) {
        return $this->getCipher()->encrypt($text);
    }

    public function decrypt($text) {
        return $this->getCipher()->decrypt($text);
    }

}
