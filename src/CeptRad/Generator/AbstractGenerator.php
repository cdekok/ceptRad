<?php
namespace CeptRad\Generator;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Code\Generator\FileGenerator;
use Zend\Filter\Word\UnderscoreToCamelCase;

abstract class AbstractGenerator implements GeneratorInterface, EventManagerAwareInterface
{
    /**
     *
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * File
     * @var FileGenerator
     */
    protected $file;

    /**
     * Write code to file
     *
     * @throws Exception\RuntimeException
     * @param path to save $path
     */
    public function write($file)
    {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                throw new Exception\RuntimeException('Could not create directory:'.$dir);
            }
        }

        if ($file == '' || !is_writable(dirname($file))) {
            throw new Exception\RuntimeException('This code generator object is not writable.');
        }
        file_put_contents($file, $this->getFile()->generate());
        return $this;
    }

    /**
     * Set event manager
     * @param EventManagerInterface $eventManager
     * @return \CeptRad\Generator\Generator
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
     * Get file object
     * @return FileGenerator
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file object
     * @param \Zend\Code\Generator\FileGenerator $file
     * @return \CeptRad\Generator\Generator
     */
    public function setFile(FileGenerator $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Convert underscores name to camelcase to get the proper class name
     * for tables with underscores
     *
     * @param string $string
     * @return string
     */
    public function underscoreToCamelCase($string)
    {
        $filter = new UnderscoreToCamelCase();
        return $filter->filter($string);
    }
}
