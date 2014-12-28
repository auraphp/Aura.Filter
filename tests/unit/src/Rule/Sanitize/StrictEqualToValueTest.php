<?php
namespace Aura\Filter\Rule\Sanitize;

class StrictEqualToValueTest extends AbstractSanitizeTest
{
    protected $other_value = '1';

    protected function getArgs()
    {
        return array($this->other_value);
    }

    public function providerTo()
    {
        return array(
            array(0,     true, '1'),
            array(1,     true, '1'),
            array('1',   true, '1'),
            array(true,  true, '1'),
            array(false, true, '1'),
        );
    }
}
