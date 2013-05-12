<?php
/*
 * CRUD Generator
 */

namespace CeptRad\Generator\Crud;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class Crud implements EventManagerAwareInterface
{
    use Cept\Traits\Options;

    /**
     *
     * @var \CeptRad\Generator\Crud\AdapterInterface
     */
    protected $adapter;

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
    public function __construct(AdapterInterface $adapter, array $options = null)
    {
        $this->adapter = $adapter;
        $this->setOptions($options);
    }

    /**
     * Get adapter
     * @return \CeptRad\Generator\Crud\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
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
    public function generate($name)
    {
        $this->generateForm($name);
        $this->generateController($name);
        $this->generateView($name);
        $this->generateRoute($name);
    }

    protected function generateForm($name)
    {
    }

    protected function generateController($name)
    {
    }

    protected function generateView($name)
    {
    }

    protected function generateRoute($name)
    {
    }
}
