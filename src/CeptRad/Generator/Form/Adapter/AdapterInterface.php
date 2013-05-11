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
}
