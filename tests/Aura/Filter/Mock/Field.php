<?php
/**
 * 
 * This file is part of the Aura project for PHP.
 * 
 * @package Aura.Input
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Filter\Mock;

/**
 * 
 * A single field in a fieldset.
 * 
 * @package Aura.Input
 * 
 */
class Field extends AbstractInput
{
    /**
     * 
     * The field type.
     * 
     * @var string
     * 
     */
    protected $type;
    
    /**
     * 
     * HTML attributes for the field as key-value pairs. The key is the
     * attribute name and the value is the attribute value.
     * 
     * @var array
     * 
     */
    protected $attribs = [];
    
    /**
     * 
     * Options for the field as key-value pairs (typically for select and
     * radio elements). The key is the option value and the value is the
     * option label.  Nested options may be honored as the key being an
     * optgroup label and the array value as the options under that optgroup.
     * 
     * @var array
     * 
     */
    protected $options = [];
    
    /**
     * 
     * The value for the field.  This may or may not be the same as the
     * 'value' attribue.
     * 
     * @var mixed
     * 
     */
    protected $value;
    
    /**
     * 
     * Constructor.
     * 
     * @param string $type The field type.
     * 
     */
    public function __construct($type)
    {
        $this->type = $type;
    }
    
    /**
     * 
     * Fills this field with a value.
     * 
     * @param mixed $value The value for the field.
     * 
     * @return void
     * 
     */
    public function fill($value)
    {
        $this->value = $value;
    }
    
    /**
     * 
     * Reads the value from this field.
     * 
     * @return mixed
     * 
     */
    public function read()
    {
        return $this->value;
    }
    
    /**
     * 
     * Returns this field as a plain old PHP array for use in a view.
     * 
     * @return array An array with keys `'type'`, `'name'`, `'attribs'`, 
     * `'options'`, and `'value'`.
     * 
     */
    public function get()
    {
        $attribs = array_merge(
            [
                // force a particular order on some attributes
                'id'   => null,
                'type' => null,
                'name' => null,
            ],
            $this->attribs
        );
        
        return [
            'type'          => $this->type,
            'name'          => $this->getFullName(),
            'attribs'       => $attribs,
            'options'       => $this->options,
            'value'         => $this->value,
        ];
    }
    
    /**
     * 
     * Sets the HTML attributes on this field.
     * 
     * @param array $attribs HTML attributes for the field as key-value pairs;
     * the key is the attribute name and the value is the attribute value.
     * 
     * @return self
     * 
     */
    public function setAttribs(array $attribs)
    {
        $this->attribs = $attribs;
        return $this;
    }
    
    /**
     * 
     * Sets the value options for this field, typically for select and radios.
     * 
     * @param array $options Options for the field as key-value pairs. The key
     * is the option value and the value is the option label.  Nested options
     * may be honored as the key being an optgroup label and the array value
     * as the options under that optgroup.
     * 
     * @return self
     * 
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }
    
    /**
     * 
     * Sets the value on this field.
     * 
     * @param mixed $value The value for the field.
     * 
     * @return self
     * 
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
