<?php

namespace User\Controller;

use Infomantra\Controller\AppController;
use Zend\View\Model\ViewModel;

class DashboardController extends AppController {

    public function indexAction() {

        $temp = array
            (
            'item-1' => array
                (
                'id' => 1,
                'title' => 'Item 1',
                'order' => 3
            ),
            'item-2' => array
                (
                'id' => 2,
                'title' => 'Item 2',
                'order' => 2
            ),
            'item-3' => array
                (
                'id' => 3,
                'title' => 'Item 3',
                'order' => 1
            )
        );

        return new ViewModel();
    }

}
