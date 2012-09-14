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
namespace Aura\Filter;

use StdClass;

/**
 * 
 * Abstract Rule
 * 
 * @package Aura.Filter
 * 
 */
abstract class AbstractRule implements RuleInterface
{
    /**
     * 
     * The full set of data to be filtered.
     *
     * @var object
     * 
     */
    protected $data;

    /**
     * 
     * The field to be filtered within the data.
     *
     * @var string
     * 
     */
    protected $field;

    /**
     * 
     * The message to use validate or sanitize fails.
     *
     * @var string
     * 
     */
    protected $message;

    /**
     * 
     * Prepare the rule for reuse.
     * 
     * @param StdClass $data The full set of data to be filtered.
     * 
     * @param string $field The field to be filtered within the data.
     * 
     * @return void
     * 
     */
    public function prep(StdClass $data, $field)
    {
        $this->data = $data;
        $this->field = $field;
    }

    /**
     * 
     * Get the error message; note that this returns the message whether or
     * not there was an error when validating or sanitizing.
     * 
     * @return string
     * 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 
     * Get the value of the field being filtered, or null if the field is
     * not set in the data.
     * 
     * @return mixed
     * 
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
     * Set value of field, creating it in the data if needed.
     * 
     * @param string $value The new value of the field.
     * 
     * @return void
     * 
     */
    public function setValue($value)
    {
        $field = $this->field;
        $this->data->$field = $value;
    }

    /**
     * 
     * Is the value valid?
     * 
     * @return bool True if valid, false if not valid.
     * 
     */
    public function is()
    {
        return call_user_func_array([$this, 'validate'], func_get_args());
    }

    /**
     * 
     * Is the value *not* valid?
     * 
     * @return bool True if not valid, false if valid.
     * 
     */
    public function isNot()
    {
        return ! call_user_func_array([$this, 'validate'], func_get_args());
    }

    /**
     * 
     * Is the value blank, or otherwise valid?
     * 
     * @return bool True if blank or valid, false if not.
     * 
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
     * Sanitize the value, transforming it as needed.
     * 
     * @return bool True if the value was sanitized, false if not.
     * 
     */
    public function fix()
    {
        return call_user_func_array([$this, 'sanitize'], func_get_args());
    }

    /**
     * 
     * If the value is blank, set to null; sanitize if not blank, transforming
     * it as needed.
     * 
     * @return bool True if the value was set to null or sanitized, false if
     * not.
     * 
     */
    public function fixBlankOr()
    {
        if ($this->isBlank()) {
            $this->setValue(null);
            return true;
        }

        return call_user_func_array([$this, 'sanitize'], func_get_args());
    }

    /**
     * 
     * Is the value blank?
     * 
     * Blank is null, empty string, or a string of only whitespace. Non-null
     * non-string values are not blank; e.g., integer zero, float zero, an
     * empty array, boolean false, etc. are not blank.
     * 
     * @return bool True if blank, false if not.
     * 
     */
    protected function isBlank()
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
     * Make use of overriding, do we want to throw an exception here?
     *
     * @return bool true on success, or false on failure.
     *
     */
    protected function validate()
    {
        return false;
    }

    /**
     *
     * Make use of overriding, do we want to throw an exception here?
     *
     * @return bool true on success, or false on failure.
     *
     */
    protected function sanitize()
    {
        return false;
    }
}
