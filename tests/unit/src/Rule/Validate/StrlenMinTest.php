<?php
namespace Aura\Filter\Rule\Validate;

class StrlenMinTest extends AbstractValidateTest
{
    protected $min = 4;

    protected function getArgs()
    {
        return array($this->min);
    }

    public function providerIs()
    {
        return [
            ['abcd'],
            ['efghijkl'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            ['a'],
            ['ab'],
            ['abc'],
        ];
    }

    public function providerFix()
    {
        return [
            [array(), false, array()],
            ['a',       true, 'a   '],
            ['abcd',    true, 'abcd'],
            ['abcdefg', true, 'abcdefg'],
        ];
    }
}
