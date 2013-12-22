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
     * Set required options for code generator
     *
     * @return string
     */
    public function setRequired()
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
     * Generate module skeleton
     */
    public function moduleAction()
    {
        $config = $this->getServiceLocator()->get('ApplicationConfig');
        $modulePath = $config['module_listener_options']['module_paths'];
        $path = $modulePath;
        if (count($modulePath) > 1) {
            $option = \Zend\Console\Prompt\Select::prompt(
                'Which module path would you like to use?',
                $modulePath,
                false,
                true
            );
            $path = $modulePath[$option];
        }
        $module = $this->getRequest()->getParam('module');
        $moduleGenerator = new \CeptRad\Generator\Module\Module();
        echo 'Generating module skeleton in: '. $path.DIRECTORY_SEPARATOR.$module.PHP_EOL;
        $moduleGenerator->create($module, $path);        
    }
    
    /**
     * Scaffold CRUD code from database tables
     *
     * @return type
     */
    public function crudAction()
    {
        $this->setRequired();
        $modReflection = new \ReflectionClass($this->module);
        $srcPath = dirname($modReflection->getFileName()).'/src/';
        $namespace = $modReflection->getNamespaceName();

        $eventManager = $this->getEventManager();

        $request = $this->getRequest();
        $schema = $request->getParam('schema');
        $options = array(
            'schema' => $schema
        );

        $modReflection = new \ReflectionClass($this->module);
        $modulePath = dirname($modReflection->getFileName());
        $namespace = $modReflection->getNamespaceName();

        $crud = new \CeptRad\Generator\Crud\Crud($this->db);
        $crud->generate($modulePath, $namespace);

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
        $this->setRequired();
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
        $this->setRequired();
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
