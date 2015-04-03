<?php

namespace Infomantra\Form\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Infomantra\Constant\AppConstant;

class DateTime extends AbstractHelper {

    public function __invoke($dateTime = null, $format = AppConstant::DEFAULT_DATE_FORMAT) {
        
        if (empty($dateTime)) {
            $dateTime = date($format);
        } else {
            $dateTime = date($format, strtotime($dateTime));
        }
        return $dateTime;
    }

}
