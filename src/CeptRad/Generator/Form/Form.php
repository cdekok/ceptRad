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

    public function generate($srcPath, $nameSpace)
    {
        $forms = $this->adapter->getForms();
        foreach ($forms as $form) {
            var_dump($form);
        }
    }
}
