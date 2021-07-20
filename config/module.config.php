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
                        'child_routes' => [
                            'add-object' => [
                                'type' => \Laminas\Router\Http\Literal::class,
                                'options' => [
                                    'route' => '/add-object',
                                    'defaults' =>  [
                                        '__NAMESPACE__' => 'MPCustomInput\Controller',
                                        'controller' => Controller\IndexController::class,
                                        'action' => 'addObject',
                                    ],
                                ],
                            ],
                            'add-image-document' => [
                                'type' => \Laminas\Router\Http\Literal::class,
                                'options' => [
                                    'route' => '/add-image-document',
                                    'defaults' =>  [
                                        '__NAMESPACE__' => 'MPCustomInput\Controller',
                                        'controller' => Controller\IndexController::class,
                                        'action' => 'addImageDocument',
                                    ],
                                ],
                            ],
                            'add-text-document' => [
                                'type' => \Laminas\Router\Http\Literal::class,
                                'options' => [
                                    'route' => '/add-text-document',
                                    'defaults' =>  [
                                        '__NAMESPACE__' => 'MPCustomInput\Controller',
                                        'controller' => Controller\IndexController::class,
                                        'action' => 'addTextDocument',
                                    ],
                                ],
                            ],
                            'add-bibliographic-record' => [
                                'type' => \Laminas\Router\Http\Literal::class,
                                'options' => [
                                    'route' => '/add-bibliographic-record',
                                    'defaults' =>  [
                                        '__NAMESPACE__' => 'MPCustomInput\Controller',
                                        'controller' => Controller\IndexController::class,
                                        'action' => 'addBibliographicRecord',
                                    ],
                                ],
                            ],                            
                        ],
                    ],
                ],
            ],
        ],
    ],    
];
