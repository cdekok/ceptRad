<?php
namespace CeptRad\Controller;

class GenerateController extends AbstractConsoleController
{
    /**
     * Scaffold CRUD code from database tables
     *
     * @return type
     */
    public function crudAction()
    {
        $db = $this->getDb();
        if (!$db) {
            return 'Database not configured in service locator'.PHP_EOL;
        }
        return 'Generating CRUD...'.PHP_EOL;
    }

    /**
     * Form generator
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function formAction()
    {
        $db = $this->getDb();
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

        $eventManager = $this->getEventManager();

        $options = array(
            'schema' => $request->getParam('schema')
        );
        $generatorAdapter  = new \CeptRad\Generator\Form\Adapter\DbAdapter($db, $options);
        $generator = new \CeptRad\Generator\Form\Form($generatorAdapter, array('eventManager' => $eventManager));

        // Listen to event when form is created
        $eventManager->attach(\CeptRad\Generator\Form\Form::EVENT_POST_SAVE_FORM, array($this, 'formCreated'));
        $generator->generate($srcPath, $namespace);
        return 'Done..'.PHP_EOL;
    }

    /**
     * Notification after form creation
     *
     * @param \Zend\EventManager\Event $event
     */
    public function formCreated(\Zend\EventManager\Event $event)
    {
        $form = $event->getParam('file');
        echo 'Created form at :'.$form.PHP_EOL;
    }

    /**
     * Get database adapter
     * @return \Zend\Db\Adapter\Adapter
     */
    protected function getDb()
    {
        return $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    }
}
