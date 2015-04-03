<?php

namespace User\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use User\Form\Fieldset\Element\ResetPasswordElements;
use Infomantra\Message\Message;
use Infomantra\Constant\AppConstant;

class ResetPasswordFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        parent::__construct('ResetPasswordFieldset');

        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new ResetPasswordElements());

        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'currentPassword',
            'options' => array(
                'label' => 'Current Password',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'currentPassword',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::CURRENT_PASSWORD_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'options' => array(
                'label' => 'New Password',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'password',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::PASSWORD_IS_REQUIRED,
                "data-rule-minlength" => AppConstant::MIN_PASSWORD_LENGTH,
                "data-msg-minlength" => Message::INVALID_PASSWORD_LENGTH
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'confirmPassword',
            'options' => array(
                'label' => 'Confirm Password',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'confirmPassword',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::CONFIRM_PASSWORD_IS_REQUIRED,
                "data-rule-equalto" => '#password',
                "data-msg-equalto" => Message::CONFIRM_PASSWORD_NOT_MATCHED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'resetPasswordToken',
            'attributes' => array(
                'class' => 'required input-lg',
                'maxlength' => AppConstant::FORGOT_PASSWORD_TOKEN_LENGTH,
                "data-rule-required" => true,
                "data-msg-required" => Message::FORGOT_PASSWORD_TOKEN_MISSING,
                "data-rule-maxlength" => AppConstant::FORGOT_PASSWORD_TOKEN_LENGTH,
                "data-msg-maxlength" => Message::INVALID_FORGOT_PASSWORD_TOKEN
            )
        ));
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $minLength = \Zend\Validator\StringLength::TOO_SHORT;
        $maxLength = \Zend\Validator\StringLength::TOO_LONG;
        $identical = \Zend\Validator\Identical::NOT_SAME;

        return array(
            'currentPassword' => array(
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
                                $isEmpty => Message::CURRENT_PASSWORD_IS_REQUIRED
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
            ),
            'confirmPassword' => array(
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
                                $isEmpty => Message::CONFIRM_PASSWORD_IS_REQUIRED
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'password',
                            'messages' => array(
                                $identical => Message::CONFIRM_PASSWORD_NOT_MATCHED
                            )
                        )
                    )
                )
            ),
            'resetPasswordToken' => array(
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
                                $isEmpty => Message::FORGOT_PASSWORD_TOKEN_MISSING
                            )
                        )
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'max' => AppConstant::FORGOT_PASSWORD_TOKEN_LENGTH,
                            'messages' => array(
                                $maxLength => Message::INVALID_FORGOT_PASSWORD_TOKEN
                            )
                        )
                    )
                )
            )
        );
    }

}
