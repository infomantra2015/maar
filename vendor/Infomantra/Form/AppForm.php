<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infomantra\Form;

use Zend\Form\Form;

use Zend\Stdlib\Hydrator\ClassMethods;

class AppForm extends Form{
    
    public function init(){
        $this->setHydrator(new ClassMethods());
    }
    
}