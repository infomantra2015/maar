<?php
return array(
    'router' => array(
        'routes' => array(
            'error-page' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/error-page',
                    'defaults' => array(
                        '__NAMESPACE__' => 'ExceptionHandling\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'ExceptionHandling\Controller\Index' => 'ExceptionHandling\Controller\IndexController'
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'exception-handling/index/index' => __DIR__ . '/../view/exception-handling/index/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    )
);
