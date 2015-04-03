<?php

namespace User\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use User\Form\Fieldset\Element\ProfileElements;
use Infomantra\Message\Message;
use Infomantra\Constant\AppConstant;

class ProfileFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        parent::__construct('ProfileFieldset');

        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new ProfileElements());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'userId',
            'options' => array(
                'label' => ''
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'firstName',
            'options' => array(
                'label' => 'First Name',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'for' => ''
                )
            ),
            'attributes' => array(
                'id' => 'firstName',
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
            'type' => 'Zend\Form\Element\Date',
            'name' => 'dob',
            'options' => array(
                'label' => 'Date Of Birth',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'dob',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::DOB_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'mobileNumber',
            'options' => array(
                'label' => 'Mobile Number',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'mobileNumber',
                'maxlength' => AppConstant::MAX_PHONE_NUMBER_LENGTH,
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::MOBILE_IS_REQUIRED,
                "data-rule-maxlength" => AppConstant::MAX_PHONE_NUMBER_LENGTH,
                "data-msg-maxlength" => Message::INVALID_MOBILE,
                "data-rule-indianPhone" => true,
                "data-msg-indianPhone" => Message::INVALID_MOBILE,
            )
        ));
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $isDigit = \Zend\Validator\Digits::NOT_DIGITS;
        $inArray = \Zend\Validator\InArray::NOT_IN_ARRAY;
        $maxLength = \Zend\Validator\StringLength::TOO_LONG;
        $regExp = \Zend\Validator\Regex::INVALID;

        return array(
            'userId' => array(
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
                                $isEmpty => Message::USER_ID_IS_REQUIRED
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'messages' => array(
                                $isDigit => Message::DIGIT_REQUIRED
                            )
                        )
                    )
                )
            ),
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
            'mobileNumber' => array(
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
                                $isEmpty => Message::MOBILE_IS_REQUIRED
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'max' => AppConstant::MAX_PHONE_NUMBER_LENGTH,
                            'messages' => array(
                                $maxLength => Message::INVALID_MOBILE
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => AppConstant::MOBILE_REG_EXP,
                            'messages' => array(
                                $regExp => Message::INVALID_MOBILE
                            )
                        ),
                        'break_chain_on_failure' => true
                    )
                )
            ),
        );
    }

}
