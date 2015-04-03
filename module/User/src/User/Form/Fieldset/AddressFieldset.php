<?php

namespace User\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use User\Form\Fieldset\Element\AddressElements;
use Infomantra\Message\Message;

class AddressFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        parent::__construct('AddressFieldset');

        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new AddressElements());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'userId',
            'options' => array(
                'label' => ''
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'countryId',
            'options' => array(
                'label' => 'Country',
                'label_attributes' => array(
                    'class' => 'form-label required'
                ),
                'value_options' => array(
                    '' => 'Select country',
                    '105' => 'India'
                ),
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'id' => 'countryId',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::COUNTRY_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'stateId',
            'options' => array(
                'label' => 'State',
                'label_attributes' => array(
                    'class' => 'form-label required'
                ),
                'value_options' => array(
                    '' => 'Select state'
                ),
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'id' => 'stateId',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::STATE_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'cityId',
            'options' => array(
                'label' => 'City',
                'label_attributes' => array(
                    'class' => 'form-label required'
                ),
                'value_options' => array(
                    '' => 'Select city'
                ),
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'id' => 'cityId',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::CITY_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'address',
            'options' => array(
                'label' => 'Address',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'address',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::ADDRESS_IS_REQUIRED
            )
        ));
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $isDigit = \Zend\Validator\Digits::NOT_DIGITS;

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
            'countryId' => array(
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
                                $isEmpty => Message::COUNTRY_IS_REQUIRED
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
            'stateId' => array(
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
                                $isEmpty => Message::STATE_IS_REQUIRED
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
            'cityId' => array(
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
                                $isEmpty => Message::CITY_IS_REQUIRED
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
            'address' => array(
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
                                $isEmpty => Message::ADDRESS_IS_REQUIRED
                            )
                        )
                    )
                )
            ),
        );
    }

}
