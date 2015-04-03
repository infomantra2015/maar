<?php

namespace User\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use User\Form\Fieldset\Element\LoginElements;
use Infomantra\Message\Message;
use Infomantra\Constant\AppConstant;

class LoginFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {
        
        parent::__construct('LoginFieldset');
        
        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new LoginElements());

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
                'maxlength' => AppConstant::MAX_EMAIL_LENGTH,
                "data-rule-required" => true,
                "data-msg-required" => Message::EMAIL_IS_REQUIRED,
                "data-rule-maxlength" => AppConstant::MAX_EMAIL_LENGTH,
                "data-msg-maxlength" => Message::INVALID_EMAIL_LENGTH,
                "data-rule-email" => true,
                "data-msg-email" => Message::VALID_EMAIL_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'placeholder' => 'Password',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::PASSWORD_IS_REQUIRED,
                "data-rule-minlength" => AppConstant::MIN_PASSWORD_LENGTH,
                "data-msg-minlength" => Message::INVALID_PASSWORD_LENGTH
            )
        ));
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $minLength = \Zend\Validator\StringLength::TOO_SHORT;
        $maxLength = \Zend\Validator\StringLength::TOO_LONG;
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
                        'name' => 'StringLength',
                        'options' => array(
                            'max' => AppConstant::MAX_EMAIL_LENGTH,
                            'messages' => array(
                                $maxLength => Message::INVALID_EMAIL_LENGTH
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
            ),
            'password' => array(
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
                                $isEmpty => Message::PASSWORD_IS_REQUIRED
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => AppConstant::MIN_PASSWORD_LENGTH,
                            'messages' => array(
                                $minLength => Message::INVALID_PASSWORD_LENGTH
                            )
                        )
                    )
                )
            )
        );
    }

}
