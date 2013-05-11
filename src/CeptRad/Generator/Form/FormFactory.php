<?php
namespace CeptRad\Generator\Form;

class FormFactory
{
    /**
     * Do not allow construct
     */
    private function __construct()
    {
    }

    /**
     * Factory method for form generator
     * @param mixed $config
     * @return Form Form generator
     */
    public static function factory($param)
    {
        if ($param instanceof \Zend\Db\Adapter\AdapterInterface) {
            $db = new \Cept\Db\Db($param);
            $server = $db->getServer();
            $adapter = null;
            switch ($server) {
                case \Cept\Db\Db::DB_MYSQL:
                    $adapter = new \CeptRad\Generator\Form\Adapter\Mysql($param);
                    break;
            }
        }

        if (!$adapter) {
            throw new \InvalidArgumentException('Adapter not supported');
        }
        return new Form($adapter);
    }
}
