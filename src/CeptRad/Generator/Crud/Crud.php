<?php
/*
 * CRUD Generator
 */

namespace CeptRad\Generator\Crud;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class Crud implements EventManagerAwareInterface
{
    use \Cept\Traits\Options;

    /**
     *
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $db;

    /**
     * Db schema
     * @var string
     */
    protected $schema;

    /**
     *
     * @var \Zend\Db\Metadata\Metadata
     */
    protected $metadata;

    /**
     *
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * Base path to start writing code from, this will normally be the src directory under the module
     * @var string
     */
    protected $basePath;

    /**
     * Namespace of the module where the crud code belongs to
     * @var string
     */
    protected $namespace;

    /**
     *
     * @param \CeptRad\Generator\Crud\AdapterInterface $adapter
     * @param array $options
     */
    public function __construct(\Zend\Db\Adapter\Adapter $db, array $options = null)
    {
        $this->db = $db;
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * Get adapter
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Set db
     * @param \Zend\Db\Adapter\Adapter $db
     * @return \CeptRad\Generator\Crud\Crud
     */
    public function setDb(\Zend\Db\Adapter\Adapter $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Set event manager
     * @param EventManagerInterface $eventManager
     * @return \CeptRad\Generator\Crud\Crud
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * Get event manager
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * Base path where the crud code will be writter
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Set path where the crud code will be writter
     * @param string $basePath
     * @return \CeptRad\Generator\Crud\Crud
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
        return $this;

    }

    /**
     * Get namespace to use for all code
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set namespace of the code typically the module namespace
     * @param string $namespace
     * @return \CeptRad\Generator\Crud\Crud
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Generate CRUD code for single table
     * @param string $name
     */
    public function generate($modulePath, $namespace)
    {
        $tableGenerator = new \CeptRad\Generator\Table\Table();
        $tableFactoryGenerator = new \CeptRad\Generator\Table\TableServiceFactory();
        $controllerGenerator = new \CeptRad\Generator\Controller\Controller();
        $viewGenerator = new \CeptRad\Generator\View\Crud\ListView();

        $srcPath = $modulePath.'/src/';
        $tables = $this->getMetaData()->getTableNames($this->schema);
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

            $controllerGenerator->generate($table, $namespace.'\Controller');
            $controllerFile = $srcPath.$namespace.'/Controller/'.$tableClassname.'Controller.php';
            $controllerGenerator->write($controllerFile);
            echo 'Controller written to: '.$controllerFile.PHP_EOL;

            $viewGenerator->generate($table, $this->getMetaData()->getColumnNames($table, $this->schema), $namespace);
            $listView = $modulePath.'/view/'.$table.'/list.phtml';
            $viewGenerator->write($listView);
            echo 'View written to: '.$listView.PHP_EOL;
        }
    }

    public function getSchema()
    {
        return $this->schema;
    }

    public function setSchema($schema)
    {
        $this->schema = $schema;
        return $this;
    }

    /**
     * Get metadata
     * @return \Zend\Db\Metadata\Metadata
     */
    protected function getMetaData()
    {
        if (!$this->metadata) {
            $this->metadata = new \Zend\Db\Metadata\Metadata($this->getDb());
        }
        return $this->metadata;
    }
}
