<?php

return array(
    'router' => array(
        'routes' => array(
            'store' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/store',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Store\Controller',
                        'controller' => 'Store',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]][page/:page]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'page' => '[1-9][0-9]+'
                            ),
                            'defaults' => array(
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'wildcard' => array(
                                'type' => 'Wildcard'
                            )
                        )
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'StoreData' => 'Store\Form\Fieldset\Element\StoreElements',
            'OfferData' => 'Store\Form\Fieldset\Element\OfferElements'
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Store\Controller\Store' => 'Store\Controller\StoreController',
            'Store\Controller\Offer' => 'Store\Controller\OfferController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
