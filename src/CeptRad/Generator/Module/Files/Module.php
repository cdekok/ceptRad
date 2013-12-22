<?php
namespace CeptRad\Generator\Module\Files;

class Module extends \CeptRad\Generator\AbstractGenerator
{
    public function __construct($moduleName) {
        $file = new \Zend\Code\Generator\FileGenerator();
        $file->setNamespace($moduleName);
        
        $class = new \Zend\Code\Generator\ClassGenerator('Module');        
        
        $getConfig = new \Zend\Code\Generator\MethodGenerator('getConfig');
        $getConfig->setBody('return include __DIR__ . \'/config/module.config.php\';');                
        
        $getAutoloaderConfig = new \Zend\Code\Generator\MethodGenerator('getAutoloaderConfig');
        $getAutoloaderConfig->setBody('return [
    \'Zend\Loader\StandardAutoloader\' => [
        \'namespaces\' => [
            __NAMESPACE__ => __DIR__ . \'/src/\' . __NAMESPACE__,
        ],
    ],
];');
        
        $class->addMethodFromGenerator($getConfig)
            ->addMethodFromGenerator($getAutoloaderConfig);
        
        $file->setClass($class);
        $this->setFile($file);
    }
}