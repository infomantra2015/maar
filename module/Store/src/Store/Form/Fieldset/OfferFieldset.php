<?php

namespace Store\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Store\Form\Fieldset\Element\OfferElements;
use Infomantra\Message\Message;

class OfferFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        parent::__construct('OfferFieldset');

        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new OfferElements());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'offerId',
            'options' => array(
                'label' => ''
            ),
            'attributes' => array(
                'id' => 'offerId',
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'offerTitle',
            'options' => array(
                'label' => 'Title',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                ),
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::TITLE_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'offerDescription',
            'options' => array(
                'label' => 'Offer Description',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                )
            ),
            'attributes' => array(
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::DESCRIPTION_IS_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'validFrom',
            'options' => array(
                'label' => 'Valid From',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                )
            ),
            'attributes' => array(
                'readonly' => 'readonly',
                'id' => 'validFrom',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::OFFER_VALID_FROM_REQUIRED
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'validTo',
            'options' => array(
                'label' => 'Valid To',
                'label_attributes' => array(
                    'class' => 'form-label required',
                    'style' => 'width:100%'
                )
            ),
            'attributes' => array(
                'id' => 'validTo',
                'readonly' => 'readonly',
                'class' => 'form-control required input-lg',
                "data-rule-required" => true,
                "data-msg-required" => Message::OFFER_VALID_TO_REQUIRED,
                "data-rule-greaterThan" => "#validFrom",
                "data-msg-greaterThan" => Message::INVALID_TO_DATE,
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
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;

        return array(
            'offerTitle' => array(
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
                                $isEmpty => Message::TITLE_IS_REQUIRED
                            )
                        )
                    )
                )
            ),
            'offerDescription' => array(
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
        );
    }

}
