<?php
namespace Aura\Filter\Mock;

class Fieldset
{
    protected $inputs = [];
    
    public function __construct()
    {
        $this->inputs['name'] = new \Aura\Filter\Mock\Field('Hari KT');
        $this->inputs['password'] = new \Aura\Filter\Mock\Field('hari1234');
    }
}
