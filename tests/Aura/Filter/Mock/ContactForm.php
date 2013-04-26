<?php
namespace Aura\Filter\Mock;

use Aura\Input\Form;

class ContactForm extends Form
{
    public function init()
    {
        $this->setField('name')
            ->setAttribs([
                'id' => 'name',
                'size' => 20,
                'maxlength' => 20,
            ]);
        $this->setField('password')
            ->setAttribs([
                'password' => 'password',
            ]);
        $this->setField('submit', 'submit')
            ->setAttribs(['value' => 'send']);
        
        $filter = $this->getFilter();
        
        $filter->addSoftRule('name', $filter::IS, 'string');
        $filter->addSoftRule('name', $filter::IS, 'strlenMin', 4);
        $filter->addSoftRule('password', $filter::FIX, 'strlenMin', 6);
    }
}
