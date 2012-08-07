<?php
/**
 * 
 * This file is part of the Aura project for PHP.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Filter\Rule;

use StdClass;

/**
 * 
 * Abstract Rule
 * 
 * @package Aura.Filter
 * 
 */
abstract class AbstractRule
{
    /**
     * 
     * A standard class of data
     *
     * @var StdClass
     */
    protected $data;

    /**
     * 
     * field name
     *
     * @var string
     */
    protected $field;

    /**
     * 
     * Error message
     *
     * @var string
     */
    protected $message;

    /**
     * 
     * prepare the rule for reuse
     * 
     * @param StdClass $data
     * 
     * @param string $field
     */
    public function prep(StdClass $data, $field)
    {
        $this->data = $data;
        $this->field = $field;
    }

    /**
     * 
     * Get the error message
     * 
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 
     * Get the value of the field
     * 
     * @return null|string
     */
    public function getValue()
    {
        $field = $this->field;
        if (isset($this->data->$field)) {
            return $this->data->$field;
        } else {
            return null;
        }
    }

    /**
     * 
     * Set value of field
     * 
     * @param string $value
     */
    public function setValue($value)
    {
        $field = $this->field;
        $this->data->$field = $value;
    }

    /**
     * 
     * check whether the rule is right
     * 
     * @return bool
     */
    public function is()
    {
        return call_user_func_array([$this, 'validate'], func_get_args());
    }

    /**
     * 
     * check the rule is wrong
     * 
     * @return bool
     */
    public function isNot()
    {
        return ! call_user_func_array([$this, 'validate'], func_get_args());
    }

    /**
     * 
     * checks whether the field value is blank
     * 
     * @return boolean
     */
    public function isBlank()
    {
        $value = $this->getValue();

        // nulls are blank
        if (is_null($value)) {
            return true;
        }

        // non-strings are not blank: int, float, object, array, resource, etc
        if (! is_string($value)) {
            return false;
        }

        // strings that trim down to exactly nothing are blank
        return trim($value) === '';
    }

    /**
     * 
     * check whether its blank or check rule is right
     * 
     * @return boolean
     */
    public function isBlankOr()
    {
        if ($this->isBlank()) {
            return true;
        } else {
            return call_user_func_array([$this, 'validate'], func_get_args());
        }
    }

    /**
     * 
     * try to sanitize the field
     * 
     * @return bool
     */
    public function fix()
    {
        return call_user_func_array([$this, 'sanitize'], func_get_args());
    }

    /**
     * 
     * if blank set to null or try to sanitiza field
     * 
     * @return boolean
     */
    public function fixBlankOr()
    {
        if ($this->isBlank()) {
            $this->setValue(null);
            return true;
        }

        return call_user_func_array([$this, 'sanitize'], func_get_args());
    }
}

