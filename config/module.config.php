<?php
return array(
  'console' => array(
        'router' => array(
            'routes' => array(
                'generate-forms' => array(
                    'options' => array(
                        'route'    => 'rad form <module>',
                        'defaults' => array(
                            'controller' => 'CeptRad\Controller\GenerateController',
                            'action'     => 'form'
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
