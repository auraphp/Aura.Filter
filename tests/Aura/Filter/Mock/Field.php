<?php
namespace Aura\Filter\Mock;

class Field
{
    protected $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
}
