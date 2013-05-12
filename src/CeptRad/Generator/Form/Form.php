<?php
namespace CeptRad\Generator\Form;

use CeptRad\Generator\Form\Adapter\AdapterInterface;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Filter\Word\UnderscoreToCamelCase;

class Form implements EventManagerAwareInterface
{
    use \Cept\Traits\Options;

    /**
     * Form adapter
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Event manager
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter, array $options = null)
    {
        $this->adapter = $adapter;
        $this->setOptions($options);
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
        $file = new FileGenerator();
        $file->setUses(array('Zend\Form\Form'));
        $formNamespace = $namespace.'\Form';
        $file->setNamespace($formNamespace);

        $className = $this->underscoreToCamelCase($form);
        $class = new ClassGenerator($className);
        $class->setExtendedClass('Form');

        $initElements = new MethodGenerator();
        $initElements->setName('initElements');
        $initElements->setBody($this->getBodyInitElements($form));

        $class->addMethodFromGenerator($initElements);

        $file->setClass($class);
        $namespaceDir = str_replace('\\', '/', $formNamespace);
        $fileDir = $srcPath.$namespaceDir.'/';
        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0644, true);
        }
        $file->setFilename($fileDir.$className.'.php');
        $file->write();
    }

    /**
     * Get body for all form element
     * @param string $form
     * @return string
     */
    protected function getBodyInitElements($form)
    {
        $fields = $this->adapter->getFormFields($form);
        $bodyStr = '';
        foreach ($fields as $field) {
            $bodyStr .= $this->getBodyElement($field)."\n";
        }
        return $bodyStr;
    }

    /**
     * Get body for one form element
     * @param string $element
     * @return string
     */
    protected function getBodyElement($element)
    {
        $elementArray = array(
            'name' => $element,
            'attributes' => array(
                'type' => 'text'
            )
        );

        $valGenerator = new \Zend\Code\Generator\ValueGenerator($elementArray);
        $bodyStr = '$this->add('.$valGenerator->generate().');';
        return $bodyStr;
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


    /**
     * Set event manager
     * @param EventManagerInterface $eventManager
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
}
