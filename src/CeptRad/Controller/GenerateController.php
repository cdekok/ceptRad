<?php
namespace CeptRad\Controller;

class GenerateController extends AbstractConsoleController
{
    public function formAction()
    {
        // Check if we have a db
        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        if (!$db) {
            return 'Database not configured in service locator'.PHP_EOL;
        }

        $request = $this->getRequest();
        $moduleName = $request->getParam('module');

        // Check if the specified module exists
        /* @var $moduleManager \Zend\ModuleManager\ModuleManager  */
        $moduleManager = $this->getServiceLocator()->get('ModuleManager');
        $module = $moduleManager->getModule($moduleName);

        if (!$module) {
            throw new \InvalidArgumentException('Module not found: '.$moduleName);
        }

        $modReflection = new \ReflectionClass($module);
        $srcPath = dirname($modReflection->getFileName()).'/src/';
        $namespace = $modReflection->getNamespaceName();

        $generator  = \CeptRad\Generator\Form\FormFactory::factory($db);
        $generator->generate($srcPath, $namespace);
        return 'Do something'.PHP_EOL;
    }
}
