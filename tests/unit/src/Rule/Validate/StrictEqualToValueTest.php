<?php
namespace Aura\Filter\Rule\Validate;

class StrictEqualToValueTest extends AbstractValidateTest
{
    protected $other_value = '1';

    protected function getArgs()
    {
        return array($this->other_value);
    }

    public function providerIs()
    {
        return [
            ['1'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [1],
            [true],
            [1.00],
        ];
    }

    public function providerFix()
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
