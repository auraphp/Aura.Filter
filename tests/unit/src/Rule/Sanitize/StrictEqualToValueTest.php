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
        return [
            [0,     true, '1'],
            [1,     true, '1'],
            ['1',   true, '1'],
            [true,  true, '1'],
            [false, true, '1'],
        ];
    }
}
