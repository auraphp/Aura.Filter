<?php
namespace Aura\Filter\Rule\Validate;

class StrlenTest extends AbstractValidateTest
{
    protected $len = 4;

    protected function getArgs()
    {
        return array($this->len);
    }

    public function providerIs()
    {
        return [
            ['abcd'],
            ['efgh'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            ['abc'],
            ['defgh'],
        ];
    }

    public function providerFix()
    {
        return [
            [array(),   false, array()],
            ['a',       true, 'a   '],
            ['abcd',    true, 'abcd'],
            ['abcdef',  true, 'abcd'],
        ];
    }
}
