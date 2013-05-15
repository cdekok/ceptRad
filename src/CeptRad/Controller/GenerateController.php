<?php
namespace CeptRad\Controller;

class GenerateController extends AbstractConsoleController
{

    /**
     * @var \Zend\Db\ADapter\Adapter
     */
    protected $db;

    /**
     * Module where the codes needs to be generated
     * @var mixed
     */
    protected $module;

    /**
     * Set event manager
     *
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function setEventManager(\Zend\EventManager\EventManagerInterface $events)
    {
        // Set required properties for crud generator
        $events->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setRequired'));
        parent::setEventManager($events);
    }

    /**
     * Set required options for code generator
     *
     * @param \Zend\Mvc\MvcEvent $e
     * @return type
     */
    public function setRequired(\Zend\Mvc\MvcEvent $e)
    {
        $response = $this->getResponse();
        $this->db = $this->getDb();
        if (!$this->db) {
            return $response->setContent('Database not configured in service locator'.PHP_EOL);
        }

        $request = $this->getRequest();
        $moduleName = $request->getParam('module');

        // Check if the specified module exists
        /* @var $moduleManager \Zend\ModuleManager\ModuleManager  */
        $moduleManager = $this->getServiceLocator()->get('ModuleManager');
        $this->module = $moduleManager->getModule($moduleName);

        if (!$this->module) {
            return $response->setContent('Module not found, make sure it is enabled in the application config: '.$moduleName.PHP_EOL);
        }
    }

    /**
     * Scaffold CRUD code from database tables
     *
     * @return type
     */
    public function crudAction()
    {
        $modReflection = new \ReflectionClass($this->module);
        $srcPath = dirname($modReflection->getFileName()).'/src/';
        $namespace = $modReflection->getNamespaceName();

        $eventManager = $this->getEventManager();

        $request = $this->getRequest();
        $options = array(
            'schema' => $request->getParam('schema')
        );

        $meta = new \Zend\Db\Metadata\Metadata($this->getDb());
        $tables = $meta->getTableNames($options['schema']);

        $modReflection = new \ReflectionClass($this->module);
        $srcPath = dirname($modReflection->getFileName()).'/src/';
        $namespace = $modReflection->getNamespaceName();

        $tableGenerator = new \CeptRad\Generator\Table\Table();
        $tableFactoryGenerator = new \CeptRad\Generator\Table\TableServiceFactory();
        foreach ($tables as $table) {
            $tableGenerator->generate($table, $namespace.'\Db\TableGateway');
            $tableClassname = $tableGenerator->underscoreToCamelCase($table);
            $tableFile = $srcPath.$namespace.'/Db/TableGateway/'.$tableClassname.'.php';
            $tableGenerator->write($tableFile);
            echo 'Table written to: '.$tableFile.PHP_EOL;
            $tableFactoryGenerator->generate($tableFile, $namespace.'\Db\TableGateway');
            $tableFactoryFile = $srcPath.$namespace.'/Db/TableGateway/'.$tableClassname.'ServiceFactory.php';
            $tableFactoryGenerator->write($tableFactoryFile);
            echo 'Table factory  written to: '.$tableFactoryFile.PHP_EOL;
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
        $modReflection = new \ReflectionClass($this->module);
        $srcPath = dirname($modReflection->getFileName()).'/src/';
        $namespace = $modReflection->getNamespaceName();

        $eventManager = $this->getEventManager();

        $options = array(
            'schema' => $request->getParam('schema')
        );
        $generatorAdapter  = new \CeptRad\Generator\Form\Adapter\DbAdapter($this->db, $options);
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
