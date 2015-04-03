<?php

namespace User\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use User\Form\Fieldset\Element\ForgotPasswordElements;
use Infomantra\Message\Message;
use Infomantra\Constant\AppConstant;

class ForgotPasswordFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        parent::__construct('ForgotPasswordFieldset');

        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new ForgotPasswordElements());

        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'placeholder' => 'Email',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::EMAIL_IS_REQUIRED,
                "data-rule-email" => true,
                "data-msg-email" => Message::VALID_EMAIL_REQUIRED
            )
        ));
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $invalidEmail = \Zend\Validator\EmailAddress::INVALID;

        return array(
            'email' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                $isEmpty => Message::EMAIL_IS_REQUIRED
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'messages' => array(
                                $invalidEmail => Message::VALID_EMAIL_REQUIRED
                            )
                        )
                    )
                )
            )
        );
    }

}
