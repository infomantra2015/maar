<?php

namespace Store\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Store\Form\Fieldset\Element\StoreElements;
use Infomantra\Message\Message;

class StoreFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        parent::__construct('StoreFieldset');

        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new StoreElements());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'storeId',
            'options' => array(
                'label' => ''
            ),
            'attributes' => array(
                'id' => 'storeId',
                'class' => 'storeElement'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'countryId',
            'options' => array(
                'label' => 'Country',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                ),
                'value_options' => array(
                    '' => 'Select country',
                    '105' => 'India'
                ),
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'onchange' => 'return getContent(this, "country")',
                'class' => 'form-control countryId storeElement required input-lg',
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
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                ),
                'value_options' => array(
                    '' => 'Select state'
                ),
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'onchange' => 'return getContent(this, "state")',
                'class' => 'form-control stateId storeElement required input-lg',
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
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                ),
                'value_options' => array(
                    '' => 'Select city'
                ),
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'class' => 'form-control cityId storeElement required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::CITY_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'storeName',
            'options' => array(
                'label' => 'Store Name',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                )
            ),
            'attributes' => array(
                'class' => 'form-control storeElement required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::STORE_NAME_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'storeDescription',
            'options' => array(
                'label' => 'Store Description',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                )
            ),
            'attributes' => array(
                'class' => 'form-control storeElement required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::DESCRIPTION_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'storeAddress',
            'options' => array(
                'label' => 'Store Address',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                )
            ),
            'attributes' => array(
                'class' => 'form-control storeElement required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::ADDRESS_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'status',
            'options' => array(
                'label' => 'Status',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                ),
                'value_options' => array(
                    '' => 'Select Status',
                    'active' => 'Active',
                    'inactive' => 'In Active'
                ),
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::STATUS_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'categoryId',
            'options' => array(
                'label' => 'Category',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                ),
                'value_options' => array(
                    '' => 'Select category'
                ),
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::CATEGORY_IS_REQUIRED
            )
        ));
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $isDigit = \Zend\Validator\Digits::NOT_DIGITS;

        return array(
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
            'categoryId' => array(
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
                                $isEmpty => Message::CATEGORY_IS_REQUIRED
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
            'storeName' => array(
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
                                $isEmpty => Message::STORE_NAME_IS_REQUIRED
                            )
                        )
                    )
                )
            ),
            'storeAddress' => array(
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
            'storeDescription' => array(
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
                                $isEmpty => Message::DESCRIPTION_IS_REQUIRED
                            )
                        )
                    )
                )
            ),
            'status' => array(
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
                                $isEmpty => Message::STATUS_IS_REQUIRED
                            )
                        )
                    )
                )
            ),
        );
    }

}
