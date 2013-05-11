<?php
namespace CeptRad\Generator\Form;

use Cept\Db\Db;
use CeptRad\Generator\Form\Adapter\Mysql;
use InvalidArgumentException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\EventManager\EventManagerInterface;
use ZendTest\Form\TestAsset\Annotation\Form;

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
    public static function factory($param, EventManagerInterface $eventManager)
    {
        if ($param instanceof AdapterInterface) {
            $db = new Db($param);
            $server = $db->getServer();
            $adapter = null;
            switch ($server) {
                case Db::DB_MYSQL:
                    $adapter = new Mysql($param);
                    break;
            }
        }

        if (!$adapter) {
            throw new InvalidArgumentException('Adapter not supported');
        }
        return new Form($adapter, $eventManager);
    }
}
