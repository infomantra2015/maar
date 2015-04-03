<?php

namespace User\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use User\Form\Fieldset\Element\SocialElements;
use Infomantra\Message\Message;

class SocialFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {

        parent::__construct('SocialFieldset');

        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new SocialElements());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'userId',
            'options' => array(
                'label' => ''
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Url',
            'name' => 'facebookUrl',
            'options' => array(
                'label' => 'Facebook Profile',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'facebookUrl',
                'class' => 'form-control required input-lg',
                "data-rule-required" => false
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Url',
            'name' => 'twitterUrl',
            'options' => array(
                'label' => 'Twitter Profile',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'twitterUrl',
                'class' => 'form-control required input-lg',
                "data-rule-required" => false
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Url',
            'name' => 'linkedinUrl',
            'options' => array(
                'label' => 'Linkedin Profile',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'linkedinUrl',
                'class' => 'form-control required input-lg',
                "data-rule-required" => false
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Url',
            'name' => 'googlePlusUrl',
            'options' => array(
                'label' => 'Google+ Profile',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'googlePlusUrl',
                'class' => 'form-control required input-lg',
                "data-rule-required" => false
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Url',
            'name' => 'websiteUrl',
            'options' => array(
                'label' => 'Website Url',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'websiteUrl',
                'class' => 'form-control required input-lg',
                "data-rule-required" => false
            )
        ));
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $isDigit = \Zend\Validator\Digits::NOT_DIGITS;
        $isUrl = \Zend\Validator\Uri::INVALID;

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
            'websiteUrl' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Uri',
                        'options' => array(
                            'messages' => array(
                                $isUrl => Message::VALID_URL_REQUIRED
                            )
                        )
                    )
                )
            ),
            'facebookUrl' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Uri',
                        'options' => array(
                            'messages' => array(
                                $isUrl => Message::VALID_URL_REQUIRED
                            )
                        )
                    )
                )
            ),
            'twitterUrl' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Uri',
                        'options' => array(
                            'messages' => array(
                                $isUrl => Message::VALID_URL_REQUIRED
                            )
                        )
                    )
                )
            ),
            'linkedinUrl' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Uri',
                        'options' => array(
                            'messages' => array(
                                $isUrl => Message::VALID_URL_REQUIRED
                            )
                        )
                    )
                )
            ),
            'googlePlusUrl' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Uri',
                        'options' => array(
                            'messages' => array(
                                $isUrl => Message::VALID_URL_REQUIRED
                            )
                        )
                    )
                )
            )
        );
    }

}
