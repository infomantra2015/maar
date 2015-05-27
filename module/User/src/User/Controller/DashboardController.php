<?php

namespace User\Controller;

use Infomantra\Controller\AppController;
use Zend\View\Model\ViewModel;

class DashboardController extends AppController {

    public function indexAction() {
        $userId = $this->_getLoggedUserDetails('user_id');
        
        $totalStoreCount = $this->_getStoreCount($userId);  
        $totalOfferCount = $this->_getOfferCount($userId);  
        $dashboardData = array(
                                'totalStoreCount' => $totalStoreCount,
                                'totalOfferCount' => $totalOfferCount
                            );
        
        $viewModel = new ViewModel();
        $viewModel->setVariable('dashboardData', $dashboardData);
        return $viewModel;
    }

}
