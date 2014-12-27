<?php
namespace Aura\Filter\Rule\Validate;

class StrlenMaxTest extends AbstractValidateTest
{
    protected $max = 3;

    protected function getArgs()
    {
        return array($this->max);
    }

    public function providerIs()
    {
        return [
            ['a'],
            ['ab'],
            ['abc'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            ['abcd'],
            ['abcdefg'],
        ];
    }

    public function providerFix()
    {
        return [
            [array(), false, array()],
            ['a',       true, 'a'],
            ['abc',     true, 'abc'],
            ['abcd',    true, 'abc'],
            ['abcdefg', true, 'abc'],
        ];
    }
}
