<?php
namespace Aura\Filter\Rule;

use DateTime as PhpDateTime;

class DateTime extends AbstractRule
{
    protected $message = 'FILTER_DATETIME';
    
    protected function validate()
    {
        $value = $this->getValue();
        
        if ($value instanceof PhpDateTime) {
            return true;
        }
        
        if (! is_scalar($value)) {
            return false;
        }
        
        if (trim($value) === '') {
            return false;
        }
        
        return (bool) date_create($value);
    }
    
    protected function sanitize($format = 'Y-m-d H:i:s')
    {
        $value = $this->getValue();
        
        if ($value instanceof PhpDateTime) {
            $datetime = $value;
        } elseif (! is_scalar($value)) {
            return false;
        } else {
            $datetime = date_create($value);
        }
        
        if (! $datetime) {
            return false;
        }
        
        $this->setValue($datetime->format($format));
        return true;
    }
}
