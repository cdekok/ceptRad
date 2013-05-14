<?php
namespace CeptRad\Generator\Crud\Adapter;

class DbAdapter implements AdapterInterface
{
    /**
     * Allow setOptions
     */
    use \Cept\Traits\Options;

    /**
     * Db
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $adapter;

    /**
     * Metadata
     * @var Metadata
     */
    protected $metadata;

    /**
     * Database schema
     * @var string
     */
    protected $schema;

    /**
     *
     * @param \Zend\Db\Adapter\Adapter $adapter
     * @param array $options
     */
    public function __construct(\Zend\Db\Adapter\Adapter $adapter, array $options = null)
    {
        $this->adapter = $adapter;
        $this->setOptions($options);
    }

    /**
     * Get all element names to CRUD
     *
     * @return array
     */
    public function getElements()
    {
        return $this->getMetadata()->getTableNames($this->schema);
    }

    /**
     * Get database metadata
     * @return Metadata
     */
    protected function getMetadata()
    {
        if (!$this->metadata) {
            $this->metadata = new Metadata($this->adapter);
        }
        return $this->metadata;
    }
}
