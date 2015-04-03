<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'User\Controller\Login',
                        'action' => 'index',
                    ),
                ),
            )
        ),
    ),
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=infomantra;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'username' => 'root',
        'password' => '',
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/login' => __DIR__ . '/../view/layout/login.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'navigation' => __DIR__ . '/../view/partial/navigation.phtml',
            'pagination' => __DIR__ . '/../view/paginator/pagination.phtml',
            'modal-box' => __DIR__ . '/../view/partial/modal-box.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        )
    ),
    'white_list' => array(
        'User\Controller\Login-index',
        'User\Controller\Login-logout',
        'User\Controller\Login-process-login',
        'User\Controller\Register-index',
        'User\Controller\Register-process-registration',
        'User\Controller\ForgotPassword-index',
        'User\Controller\ForgotPassword-process-forgot-password',
        'User\Controller\ForgotPassword-reset-password',
        'User\Controller\ForgotPassword-process-reset-password',
    )
);
