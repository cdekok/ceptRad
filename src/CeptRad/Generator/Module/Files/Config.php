<?php
namespace CeptRad\Generator\Module\Files;

class Config extends \CeptRad\Generator\AbstractGenerator
{
    public function __construct() {
        $file = new \Zend\Code\Generator\FileGenerator();
        $config =  '[
    \'router\' => [
        \'routes\' => [],
    ],
    \'controllers\' => [
        \'invokables\' => []
    ],
    \'view_manager\' => [
        \'template_path_stack\' => [
            __DIR__ . \'/../view\',
        ],
    ],
];';
        $file->setBody('return '.$config);
        $this->setFile($file);
    }
}