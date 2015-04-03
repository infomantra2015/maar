<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infomantra\Utility;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Infomantra\Constant\AppConstant;

class ZendAuth implements ServiceLocatorAwareInterface {

    protected $serviceLocator;

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Authenticate user
     * 
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function authenticate($email, $password) {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $authAdapter = new AuthAdapter($dbAdapter, 'users', 'email', 'password');
        $authAdapter
                ->setIdentity($email)
                ->setCredential($password);

        $auth = $this->getServiceLocator()->get('AuthenticationService');
        $result = $auth->authenticate($authAdapter);

        if ($result->isValid()) {

            $userContent = $authAdapter->getResultRowObject(array(
                'user_id',
                'email',
                'first_name',
                'last_name'
            ));
            
            $userRoleTable = $this->getServiceLocator()->get("UserRoleTable");
            $userRoleDetails = $userRoleTable->getUserRoleName(array('user_id' => $userContent->user_id));
            
            $roleId = 0;
            $roleName = AppConstant::DEFAULT_ROLE;
            if (count($userRoleDetails) > 0) {
                $roleName = $userRoleDetails[0]['role'];
                $roleId = $userRoleDetails[0]['role_id'];
            }

            $userContent->role_id = $roleId;
            $userContent->role_name = $roleName;                    
            
            $storage = $auth->getStorage();
            $storage->write($userContent);

            return true;
        }
        return false;
    }

    /**
     * Crear login credentials
     * 
     * @access public
     * @author Arvind Singh <arvind.singh.2110@gmail.com>     * 
     * @return boolean
     */
    public function logout() {

        $auth = $this->getServiceLocator()->get('AuthenticationService');
        $storage = $auth->getStorage();

        if (!$storage->isEmpty()) {
            $storage->clear();
            return true;
        }
        return false;
    }

}
