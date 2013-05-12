<?php
return array(
  'console' => array(
        'router' => array(
            'routes' => array(
                'generate-forms' => array(
                    'options' => array(
                        'route'    => 'rad form <module> [--schema=] [--table=]',
                        'defaults' => array(
                            'controller' => 'CeptRad\Controller\GenerateController',
                            'action'     => 'form'
                        )
                    )
                ),
                'generate-crud' => array(
                    'options' => array(
                        'route'    => 'rad crud <module> [--schema=] [--table=] [--force]',
                        'defaults' => array(
                            'controller' => 'CeptRad\Controller\GenerateController',
                            'action'     => 'crud'
                        )
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'CeptRad\Controller\GenerateController' => 'CeptRad\Controller\GenerateController'
        ),
    ),
);
