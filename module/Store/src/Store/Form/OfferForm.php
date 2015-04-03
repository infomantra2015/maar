<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Store\Form;

use Infomantra\Form\AppForm;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class OfferForm extends AppForm {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name = 'OfferForm');

        $this->setAttribute('method', 'post')
                ->setHydrator(new ClassMethodsHydrator(false))
                ->setInputFilter(new InputFilter());

        $this->add(array(
            'type' => 'Store\Form\Fieldset\OfferFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            ),
            'attributes' => array(
                'class' => 'offerBox'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf'
        ));

        $this->add(array(
            'name' => 'submitBtn',
            'attributes' => array(
                'class' => 'btn btn-primary btn-flat',
                'type' => 'submit',
                'value' => 'Save',
                'id' => 'submitBtn'
            )
        ));

        $this->add(array(
            'name' => 'cancelBtn',
            'attributes' => array(
                'class' => 'btn btn-danger btn-flat',
                'type' => 'button',
                'value' => 'Cancel',
                'id' => 'cancelBtn'
            )
        ));
    }

}
