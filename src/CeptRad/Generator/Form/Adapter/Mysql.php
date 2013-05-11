<?php
namespace CeptRad\Generator\Form\Adapter;

class Mysql implements AdapterInterface
{
    /**
     * Db
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $adapter;

    /**
     *
     * @param \Zend\Db\Adapter\Adapter $adapter
     */
    public function __construct(\Zend\Db\Adapter\Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getForms()
    {
        $db = new \Cept\Db\Db($this->adapter);
        return $db->getDriver()->getTables();
    }

    /**
     * @todo implement
     * @param type $form
     */
    public function getFormFields($form)
    {
    }
}
