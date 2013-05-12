<?php
namespace CeptRad\Generator\Form\Adapter;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;

class DbAdapter implements AdapterInterface
{
    /**
     * Allow setOptions
     */
    use \Cept\Traits\Options;

    /**
     * Db
     * @var Adapter
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
     * @param Adapter $adapter
     * @param array $options
     * @param string $schema
     */
    public function __construct(Adapter $adapter, array $options = null)
    {
        $this->adapter = $adapter;
        $this->setOptions($options);
    }

    /**
     * Get form names
     * @return array
     */
    public function getForms()
    {
        return $this->getMetadata()->getTableNames($this->schema);
    }

    /**
     * Get all form fields
     * @param string $form
     * @return array
     */
    public function getFormFields($form)
    {
        return $this->getMetadata()->getColumnNames($form, $this->schema);
    }

    /**
     * Get element type
     *
     * @param string $form
     * @param string $field
     * @return string
     */
    public function getFieldType($form, $field)
    {
        $column = $this->getMetadata()->getColumn($field, $form);
        $dataType = $column->getDataType();
        $fieldType = null;
        switch ($dataType) {
            case 'text':
                $fieldType = 'Textarea';
                break;
            case 'timestamp':
                $fieldType = 'Date';
                break;
            case 'tinyint':
            case 'boolean':
                $fieldType = 'Checkbox';
                break;
            case 'text':
                $fieldType = 'Textarea';
                break;
            default:
                $fieldType = 'Text';
                break;
        }
        return $fieldType;
    }

    /**
     * Get form element validators
     *
     * @param string $form
     * @param string $element
     * @return array
     */
    public function getFieldValidators($form, $element)
    {
        $column = $this->getMetadata()->getColumn($element, $form);
        $validators = array();
        if ($column->getCharacterMaximumLength()) {
            $validators[] = array(
                'name' => 'StringLength',
                'options' => array(
                    'encoding' => 'UTF-8',
                    'max'      => $column->getCharacterMaximumLength()
                )
            );
        }
        return $validators;
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

    /**
     * Get db adapter
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Set db adapter
     * @param \Zend\Db\Adapter\Adapter $adapter
     * @return \CeptRad\Generator\Form\Adapter\AbstractDbAdapter
     */
    public function setAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Get schema
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Set schema
     * @param string $schema
     * @return \CeptRad\Generator\Form\Adapter\AbstractDbAdapter
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
        return $this;
    }
}
