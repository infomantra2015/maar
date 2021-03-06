<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace User\Form;

use Infomantra\Form\AppForm;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ResetPasswordForm extends AppForm {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name = 'ResetPasswordForm');

        $this->setAttribute('method', 'post')
                ->setHydrator(new ClassMethodsHydrator(false))
                ->setInputFilter(new InputFilter());

        $this->add(array(
            'type' => 'User\Form\Fieldset\ResetPasswordFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf'
        ));
        
        $this->add(array(
            'name' => 'resetpassword',
            'attributes' => array(
                'class' => 'btn btn-info',
                'type' => 'submit',
                'value' => 'Submit',
                'id' => 'resetPasswordBtn'
            )
        ));
    }

}
