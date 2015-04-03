<?php

return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'Login',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
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
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Login' => 'User\Controller\LoginController',
            'User\Controller\Dashboard' => 'User\Controller\DashboardController',
            'User\Controller\Register' => 'User\Controller\RegisterController',
            'User\Controller\ForgotPassword' => 'User\Controller\ForgotPasswordController',
            'User\Controller\ChangePassword' => 'User\Controller\ChangePasswordController',
            'User\Controller\Account' => 'User\Controller\AccountController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'LoginData' => 'User\Form\Fieldset\Element\LoginElements',
            'RegisterData' => 'User\Form\Fieldset\Element\RegisterElements',
            'ForgotPasswordData' => 'User\Form\Fieldset\Element\ForgotPasswordElements',
            'ResetPasswordData' => 'User\Form\Fieldset\Element\ResetPasswordElements',
            'ProfileData' => 'User\Form\Fieldset\Element\ProfileElements',
            'UserAddressData' => 'User\Form\Fieldset\Element\AddressElements',
            'UserSocialData' => 'User\Form\Fieldset\Element\SocialElements',
            'UserUploadPicData' => 'User\Form\Fieldset\Element\UploadPicureElements'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'page_title' => array(
        'User\Controller\Login' => 'Login',
        'User\Controller\Register' => 'Registration',
        'User\Controller\ForgotPassword' => 'Forgot Password',
        'User\Controller\ChangePassword' => 'Change Password',
        'User\Controller\Account' => 'Manage Account'
    )
);

