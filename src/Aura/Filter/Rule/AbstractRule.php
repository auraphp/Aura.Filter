<?php
namespace Aura\Filter\Rule;

use StdClass;

abstract class AbstractRule
{
    protected $data;
    
    protected $field;
    
    protected $message;
    
    // prepare the rule for reuse
    public function prep(StdClass $data, $field)
    {
        $this->data = $data;
        $this->field = $field;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function getValue()
    {
        $field = $this->field;
        if (isset($this->data->$field)) {
            return $this->data->$field;
        } else {
            return null;
        }
    }
    
    public function setValue($value)
    {
        $field = $this->field;
        $this->data->$field = $value;
    }
    
    public function is()
    {
        return call_user_func_array([$this, 'validate'], func_get_args());
    }
    
    public function isNot()
    {
        return ! call_user_func_array([$this, 'validate'], func_get_args());
    }
    
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
    
    public function isBlankOr()
    {
        if ($this->isBlank()) {
            return true;
        } else {
            return call_user_func_array([$this, 'validate'], func_get_args());
        }
    }
    
    public function fix()
    {
        return call_user_func_array([$this, 'sanitize'], func_get_args());
    }
    
    public function fixBlankOr()
    {
        if ($this->isBlank()) {
            $this->setValue(null);
            return true;
        }
        
        return call_user_func_array([$this, 'sanitize'], func_get_args());
    }
}
