<?php
namespace CeptRad\Generator\Table;

use CeptRad\Generator\AbstractGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

class TableServiceFactory extends AbstractGenerator
{
    /**
     * Generate table gateway service factory
     *
     * @param string $table
     * @param string $namespace
     * @return string
     */
    public function generate($table, $namespace)
    {
        $file = new FileGenerator();
        $file->setUses(
            array(
                'Zend\ServiceManager\FactoryInterface',
                'Zend\ServiceManager\ServiceLocatorInterface',
            )
        );
        $file->setNamespace($namespace);

        $className = $this->underscoreToCamelCase($table);
        $class = new ClassGenerator($className.'ServiceFactory');
        $class->setImplementedInterfaces(array('FactoryInterface'));

        $createService = new MethodGenerator('createService');
        $createServiceParams = array(
            new ParameterGenerator('servicelocator', 'ServiceLocatorInterface'),
        );
        $createService->setParameters($createServiceParams);

        $body = '$config = $serviceLocator->get(\'db\');'."\n";
        $body .= 'return $table = new CeptBlog\Db\TableGateway\User(\'user\', $db);';
        $createService->setBody($body);

        $class->addMethodFromGenerator($createService);

        $file->setClass($class);

        $this->setFile($file);

        // Trigger event post generate event
        if ($this->getEventManager()) {
            $this->getEventManager()->trigger(self::EVENT_POST_GENERATE, $this);
        }
        return $file->generate();
    }
}
