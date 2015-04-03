<?php

namespace User\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use User\Form\Fieldset\Element\RegisterElements;
use Infomantra\Message\Message;
use Infomantra\Constant\AppConstant;

class RegisterFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        parent::__construct('RegisterFieldset');

        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new RegisterElements());

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'firstName',
            'options' => array(
                'label' => 'First Name',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'placeholder' => 'First Name',
                'class' => 'form-control required input-lg',
                'maxlength' => AppConstant::MAX_FIRST_NAME_LENGTH,
                "data-rule-required" => true,
                "data-msg-required" => Message::FIRSTNAME_IS_REQUIRED,
                "data-rule-maxlength" => AppConstant::MAX_FIRST_NAME_LENGTH,
                "data-msg-maxlength" => Message::INVALID_FISRT_NAME_LENGTH,
                "data-rule-alpha" => true,
                "data-msg-alpha" => Message::ALPHABETS_REQUIRED_ONLY
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'lastName',
            'options' => array(
                'label' => 'Last Name',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'placeholder' => 'Last Name',
                'class' => 'form-control required input-lg',
                'maxlength' => AppConstant::MAX_LAST_NAME_LENGTH,
                "data-rule-required" => true,
                "data-msg-required" => Message::LASTNAME_IS_REQUIRED,
                "data-rule-maxlength" => AppConstant::MAX_LAST_NAME_LENGTH,
                "data-msg-maxlength" => Message::INVALID_LAST_NAME_LENGTH,
                "data-rule-alpha" => true,
                "data-msg-alpha" => Message::ALPHABETS_REQUIRED_ONLY
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'gender',
            'options' => array(
                'label' => 'Gender',
                'label_attributes' => array(
                    'class' => 'form-label required'
                ),
                'value_options' => array(
                    '' => 'Select gender',
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Others'
                ),
            ),
            'attributes' => array(
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::GENDER_IS_REQUIRED
            )
        ));

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
                'placeholder' => 'Confirm Password',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::CONFIRM_PASSWORD_IS_REQUIRED,
                "data-rule-equalto" => '#password',
                "data-msg-equalto" => Message::CONFIRM_PASSWORD_NOT_MATCHED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'userRoleId',
            'options' => array(
                'label' => 'Member Type',
                'label_attributes' => array(
                    'class' => 'form-label required'
                ),
                'value_options' => array(
                    array(
                        'value' => '2',
                        'label' => ' Normal User ',
                        'selected' => true,
                    ),
                    array(
                        'value' => '3',
                        'label' => ' Service Provider ',
                    )
                ),
            ),
            'attributes' => array(
                'class' => 'form-control required',
                "data-rule-required" => true,
                "data-msg-required" => Message::MEMBER_TYPE_REQUIRED,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'createdOn',
            'options' => array(
                'label' => ''
            )
        ));
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $minLength = \Zend\Validator\StringLength::TOO_SHORT;
        $maxLength = \Zend\Validator\StringLength::TOO_LONG;
        $invalidEmail = \Zend\Validator\EmailAddress::INVALID;
        $identical = \Zend\Validator\Identical::NOT_SAME;
        $inArray = \Zend\Validator\InArray::NOT_IN_ARRAY;
         
        return array(
            'firstName' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                $isEmpty => Message::FIRSTNAME_IS_REQUIRED
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'max' => AppConstant::MAX_FIRST_NAME_LENGTH,
                            'messages' => array(
                                $maxLength => Message::INVALID_FISRT_NAME_LENGTH
                            )
                        )
                    ),
                )
            ),
            'lastName' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                $isEmpty => Message::LASTNAME_IS_REQUIRED
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'max' => AppConstant::MAX_LAST_NAME_LENGTH,
                            'messages' => array(
                                $maxLength => Message::INVALID_LAST_NAME_LENGTH
                            )
                        )
                    ),
                )
            ),
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
            'gender' => array(
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
                                $isEmpty => Message::GENDER_IS_REQUIRED
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'InArray',
                        'options' => array(
                            'haystack' => array('male', 'female', 'others'),
                            'messages' => array(
                                $inArray => Message::VALID_GENDER_REQUIRED
                            )
                        )
                    )
                )
            ),
        );
    }

}
