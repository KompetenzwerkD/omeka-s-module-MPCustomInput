<?php
namespace MPCustomInput;

return [
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'controllers' => [
        'invokables' => [
            Controller\IndexController::class => Controller\IndexController::class,
        ],
    ],
    'navigation' => [
        'AdminModule' => [
            [
                'label' => 'Malaja Pereščepina', // @translate
                'route' => 'admin/mp-custom-input',
                'resource' => Controller\IndexController::class,
            ],
        ],
    ],    
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'mp-custom-input' => [
                        'type' => \Laminas\Router\Http\Literal::class,
                        'options' => [
                            'route' => '/mp-custom-input',
                            'defaults' => [
                                '__NAMESPACE__' => 'MPCustomInput\Controller',
                                'controller' => Controller\IndexController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],    
];
