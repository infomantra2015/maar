<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Infomantra\Controller\AppController;

class IndexController extends AppController {

    public function indexAction() {
        return new ViewModel();
    }

    /**
     * Get state list based on country id
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @return JsonModel
     */
    public function getStatesAction() {

        $response = array('states' => array(), 'selector' => '');

        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();
            $countryId = $data['countryId'];
            $selector = isset($data['selector']) ? $data['selector'] : '';
            $response['selector'] = $selector;
            $stateList = $this->getStates($countryId);
            if (count($stateList) > 0) {
                $response['states'] = $stateList;
            }
        }
        return new JsonModel($response);
    }

    /**
     * Get city list based on state id
     * 
     * @access public
     * @author Arvind Singh<arvind.singh.2110@gmail.com>
     * 
     * @return JsonModel
     */
    public function getCitiesAction() {

        $response = array('cities' => array(), 'selector' => '');

        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();
            $stateId = $data['stateId'];
            $selector = isset($data['selector']) ? $data['selector'] : '';
            $response['selector'] = $selector;
            $cityList = $this->getCities($stateId);

            if (count($cityList) > 0) {
                $response['cities'] = $cityList;
            }
        }
        return new JsonModel($response);
    }

}
