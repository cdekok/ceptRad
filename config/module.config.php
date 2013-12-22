<?php
return [
    'console' => [
        'router' => [
            'routes' => [
                'ceptrad-generate-module' => [
                    'options' => [
                        'route' => 'rad module <module>',
                        'defaults' => [
                            'controller' => 'CeptRad\Controller\GenerateController',
                            'action' => 'module'
                        ]
                    ]
                ],
                'ceptrad-generate-forms' => [
                    'options' => [
                        'route' => 'rad form <module> [--schema=] [--table=]',
                        'defaults' => [
                            'controller' => 'CeptRad\Controller\GenerateController',
                            'action' => 'form'
                        ]
                    ]
                ],
                'ceptrad-generate-crud' => [
                    'options' => [
                        'route' => 'rad crud <module> [--schema=] [--table=] [--force]',
                        'defaults' => [
                            'controller' => 'CeptRad\Controller\GenerateController',
                            'action' => 'crud'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'invokables' => [
            'CeptRad\Controller\GenerateController' => 'CeptRad\Controller\GenerateController'
        ],
    ],
];
