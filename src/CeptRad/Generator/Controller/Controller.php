<?php
namespace CeptRad\Generator\Controller;

use CeptRad\Generator\AbstractGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

class Controller extends AbstractGenerator
{
    /**
     * Generate controller
     *
     * @param string $controller
     * @param string $namespace
     * @return string
     */
    public function generate($controller, $namespace)
    {
        $file = new FileGenerator();
        $file->setUses(
            array(
                'Zend\Mvc\Controller\AbstractActionController',
                'Zend\View\Model\ViewModel',
            )
        );
        $file->setNamespace($namespace);

        $className = $this->underscoreToCamelCase($controller);
        $class = new ClassGenerator($className.'Controller');
        $class->setExtendedClass('AbstractActionController');

        // @todo implement body
        $createAction = new MethodGenerator('createAction');
        $class->addMethodFromGenerator($createAction);
        $listAction = new MethodGenerator('listAction');
        $class->addMethodFromGenerator($listAction);
        $deleteAction = new MethodGenerator('deleteAction');
        $class->addMethodFromGenerator($deleteAction);
        $editAction = new MethodGenerator('editAction');
        $class->addMethodFromGenerator($editAction);
        $file->setClass($class);

        $this->setFile($file);

        // Trigger event post generate event
        if ($this->getEventManager()) {
            $this->getEventManager()->trigger(self::EVENT_POST_GENERATE, $this);
        }
        return $file->generate();
    }
}
