<?php

namespace User\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use User\Form\Fieldset\Element\UploadPicureElements;
use Infomantra\Message\Message;
use Infomantra\Constant\AppConstant;

class FileUploadFieldset extends Fieldset
        implements InputFilterProviderInterface {

    public function __construct() {

        parent::__construct('FileUploadFieldset');

        $this->setHydrator(new ClassMethodsHydrator(false))->setObject(new UploadPicureElements());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'userId',
            'options' => array(
                'label' => ''
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\File',
            'name' => 'profileImage',
            'options' => array(
                'label' => 'Profile Picture',
                'label_attributes' => array(
                    'class' => 'form-label required'
                )
            ),
            'attributes' => array(
                'id' => 'profileImage',
                'multiple' => false,
                'class' => 'required',
                "data-rule-required" => true,
                "data-msg-required" => Message::FILE_IS_REQUIRED
            )
        ));
    }

    public function getInputFilterSpecification() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $isDigit = \Zend\Validator\Digits::NOT_DIGITS;
        $fileSize = \Zend\Validator\File\Size::TOO_BIG;
        $fileExtension = \Zend\Validator\File\Extension::FALSE_EXTENSION;

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
            'profileImage' => array(
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
                                $isEmpty => Message::FILE_IS_REQUIRED
                            )
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => '\Zend\Validator\File\Size',
                        'options' => array(
                            'max' => AppConstant::MAX_FILE_SIZE,
                            'messages' => array(
                                $fileSize => Message::INVALID_FILE_SIZE
                            ),
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'Zend\Validator\File\Extension',
                        'options' => array(
                            'extension' => AppConstant::getFileExtensions(),
                            'messages' => array(
                                $fileExtension => Message::INVALID_FILE_EXTENTION
                            )
                        ),
                        'break_chain_on_failure' => true
                    )
                )
            )
        );
    }

}
