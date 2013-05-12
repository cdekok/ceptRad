<?php
namespace CeptRad\Generator\Form\Adapter;

interface AdapterInterface
{
    /**
     * Get array of all form names
     *
     * @return array
     */
    public function getForms();

    /**
     * Get all fields in array from form
     *
     * @param string $form
     * @return array
     */
    public function getFormFields($form);

    /**
     * Get field element type
     *
     * @return string
     */
    public function getFieldType($form, $field);

    /**
     * Get form element validators
     *
     * @param string $form
     * @param string $field
     * @return array
     */
    public function getFieldValidators($form, $field);
}
