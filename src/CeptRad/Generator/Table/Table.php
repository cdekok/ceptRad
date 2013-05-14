<?php
namespace CeptRad\Generator\Table;

use CeptRad\Generator\AbstractGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

class Table extends AbstractGenerator
{
    /**
     * Generate table gateway class
     *
     * @param string $table
     * @param string $namespace
     */
    public function generate($table, $namespace)
    {
        $file = new FileGenerator();
        $file->setUses(
            array(
                'Zend\Db\Adapter\AdapterInterface',
                'Zend\Db\ResultSet\ResultSetInterface',
                'Zend\Db\Sql\Sql',
                'Zend\Db\TableGateway\TableGateway'
            )
        );
        $file->setNamespace($namespace);

        $className = $this->underscoreToCamelCase($table);
        $class = new ClassGenerator($className);
        $class->setExtendedClass('TableGateway');

        $construct = new MethodGenerator('__construct');
        $constructParams = array(
            new ParameterGenerator('table', 'string', $table),
            new ParameterGenerator('adapter', 'AdapterInterface'),
            new ParameterGenerator('features', null, null),
            new ParameterGenerator('resultSetPrototype', 'ResultSetInterface', null),
            new ParameterGenerator('sql', 'Sql', null),
        );
        $construct->setParameters($constructParams);
        $construct->setBody('parent::__construct($table, $adapter, $features, $resultSetPrototype, $sql);');

        $class->addMethodFromGenerator($construct);

        $file->setClass($class);

        $this->setFile($file);

        // Trigger event post generate event
        if ($this->getEventManager()) {
            $this->getEventManager()->trigger(self::EVENT_POST_GENERATE, $this);
        }
        return $file->generate();
    }
}
