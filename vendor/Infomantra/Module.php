<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Infomantra;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGatewayInterface;
use Infomantra\Constant\AppConstant;
use Zend\Mvc\MvcEvent;
use Zend\Form\FormInterface;

class Module {

    public function onBootstrap(MvcEvent $e) {

        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach(
                MvcEvent::EVENT_DISPATCH,
                array(
            $this,
            'boforeDispatch'
                ), 100
        );

        $eventManager->attach(
                MvcEvent::EVENT_RENDER,
                array(
            $this,
            'boforeRender'
                ), 100
        );
    }

    function boforeRender(MvcEvent $event) {

        $config = $event->getApplication()->getServiceManager()->get("Config");

        $controllerName = $event->getRouteMatch()->getParam('controller');
        $actionName = $event->getRouteMatch()->getParam('action');
        $routeMatch = $controllerName . '-' . $actionName;

        $title = AppConstant::APP_NAME;

        if (isset($config['page_title'][$routeMatch])) {
            $title .= "-" . $routeMatch;
        } else if (isset($config['page_title'][$controllerName])) {
            $title .= "-" . $config['page_title'][$controllerName];
        }
        $event->getViewModel()->setVariable('title', $title);
    }

    function boforeDispatch(MvcEvent $event) {

        $response = $event->getResponse();

        $appConfig = $this->getConfig();
        $whiteList = $appConfig['white_list'];

        $controller = $event->getRouteMatch()->getParam('controller');
        $action = $event->getRouteMatch()->getParam('action');

        $requestedResourse = $controller . "-" . $action;

        $auth = $event->getApplication()->getServiceManager()->get('AuthenticationService');
        $storage = $auth->getStorage()->read();

        $isLoggedIn = false;

        if (isset($storage->email) && !empty($storage->email)) {

            if ($requestedResourse == 'User\Controller\Login-index' || in_array($requestedResourse,
                            $whiteList)) {
                $url = '/user/dashboard';
                $response->setHeaders($response->getHeaders()
                                ->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
            } else {

                $userRole = (isset($storage->role_name) && !empty($storage->role_name)) ? $storage->role_name : AppConstant::DEFAULT_ROLE;

                $serviceManager = $event->getApplication()->getServiceManager();
                $acl = $serviceManager->get('ZendAcl');
                $acl->init();

                $isAllowed = $acl->isAccessAllowed($userRole, $controller,
                        $action);
                if (!$isAllowed) {
                    die('Permission denied');
                } else {
                    $isLoggedIn = true;
                }
            }
        } else {

            if ($requestedResourse != 'User\Controller\Login-index' && !in_array($requestedResourse,
                            $whiteList)) {

                $url = '/user';
                $response->setHeaders($response->getHeaders()
                                ->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
                $response->sendHeaders();
            }
        }

        $event->getViewModel()->setVariable('isLoggedIn', $isLoggedIn);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {

        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getServiceConfig() {

        return array(
            'invokables' => array(
                'UserTable' => 'Infomantra\Model\UserTable',
                'UserDetailTable' => 'Infomantra\Model\UserDetailTable',
                'RoleTable' => 'Infomantra\Model\RoleTable',
                'UserRoleTable' => 'Infomantra\Model\UserRoleTable',
                'ResourceTable' => 'Infomantra\Model\ResourceTable',
                'PermissionTable' => 'Infomantra\Model\PermissionTable',
                'RolePermissionTable' => 'Infomantra\Model\RolePermissionTable',
                'CountryTable' => 'Infomantra\Model\CountryTable',
                'StateTable' => 'Infomantra\Model\StateTable',
                'CityTable' => 'Infomantra\Model\CityTable',
                'ZendAcl' => 'Infomantra\Utility\Acl',
                'ZendAuth' => 'Infomantra\Utility\ZendAuth',
                'PasswordGenrator' => 'Infomantra\Utility\Password',
                'Cryptography' => 'Infomantra\Utility\Cryptography',
                'AuthenticationService' => 'Zend\Authentication\AuthenticationService',
                'StoreTable' => 'Infomantra\Model\StoreTable',
                'OfferTable' => 'Infomantra\Model\OfferTable',
                'StoreOfferTable' => 'Infomantra\Model\StoreOfferTable',
                'CategoryTable' => 'Infomantra\Model\CategoryTable',
            ),
            'initializers' => array(
                'InitializeModel' => function($instance, ServiceLocatorInterface $serviceLocator) {
                    if ($instance instanceof TableGatewayInterface) {
                        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
                        $instance->init($dbAdapter);
                    }
                },
                'InitializeForm' => function ($instance, ServiceLocatorInterface $serviceLocator) {
                    if ($instance instanceof FormInterface) {
                        $instance->init();
                    }
                }
            ),
            'factories' => array(
            )
        );
    }

    public function getControllerPluginConfig() {
        return array(
            'invokables' => array(
                'EmailPlugin' => 'Infomantra\Controller\Plugin\EmailPlugin',
            )
        );
    }

    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'DateTime' => 'Infomantra\Form\View\Helper\DateTime',
                'EncryptDecrypt' => 'Infomantra\Form\View\Helper\EncryptDecrypt',
            )
        );
    }

}
