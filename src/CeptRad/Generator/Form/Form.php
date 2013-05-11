<?php
namespace CeptRad\Generator\Form;

use CeptRad\Generator\Form\Adapter\AdapterInterface;

class Form
{
    /**
     * Form adapter
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Generate all forms
     *
     * @param type $srcPath
     * @param type $nameSpace
     */
    public function generate($srcPath, $namespace)
    {
        $forms = $this->adapter->getForms();
        foreach ($forms as $form) {
            $this->createForm($srcPath, $namespace, $form);
        }
    }

    /**
     * Generate single form
     *
     * @param type $srcPath
     * @param type $namespace
     * @param type $form
     * @return void
     */
    public function createForm($srcPath, $namespace, $form)
    {
        $file = new \Zend\Code\Generator\FileGenerator();
        $file->setUses(array('Zend\Form\Form'));
        $formNamespace = $namespace.'\Form';
        $file->setNamespace($formNamespace);

        $className = $this->underscoreToCamelCase($form);
        $class = new \Zend\Code\Generator\ClassGenerator($form);
        $class->setExtendedClass('Form');

        $initElements = new \Zend\Code\Generator\MethodGenerator();
        $initElements->setName('initElements');

        $class->addMethodFromGenerator($initElements);

        $file->setClass($class);
        $namespaceDir = str_replace('\\', '/', $formNamespace);
        $filePath = $srcPath.$namespaceDir.'/'.$className.'.php';
        $file->write($filePath);
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
        $filter = new \Zend\Filter\Word\UnderscoreToCamelCase();
        return $filter->filter($string);
    }
}
