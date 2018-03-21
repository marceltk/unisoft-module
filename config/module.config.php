<?php

return [
    'service_manager' => [
        'invokables' => [
            'CrawlerCronService' => \Unisoft\Crawler\Service\CrawlerCronService::class,
        ],
        'factories' => [
            "LoggerService" => function ($sm) {
                return new \Unisoft\Service\LoggerService($sm);
            },
            "CrawlerService" => function ($sm) {
                return new \Unisoft\Crawler\Service\CrawlerService($sm);
            },
        ],
        'aliases' => [
        ],
        'shared' => [
        ],
    ],

    'controllers' => [
        'factories' => [
            'Crawler\Controller\Index' => function ($sm) {
                return new Unisoft\Crawler\Controller\IndexController($sm);
            },
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'crawler' => [
                    'options' => [
                        'type' => 'simple',
                        'route' => 'crawler index [--verbose|-v]',
                        'defaults' => [
                            'controller' => 'Crawler\Controller\Index',
                            'action' => 'index',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'unisoft' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/crawler[/[:controller][/[:action]]]',
                    'defaults' => [
                        '__NAMESPACE__' => 'Crawler\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => [
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/crawler.phtml',
            'unisoft/index/index' => __DIR__ . '/../view/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

];
