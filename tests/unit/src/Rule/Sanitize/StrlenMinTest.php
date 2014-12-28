<?php
namespace Aura\Filter\Rule\Sanitize;

class StrlenMinTest extends AbstractSanitizeTest
{
    protected $min = 4;

    protected function getArgs()
    {
        return array($this->min);
    }

    public function providerTo()
    {
        return [
            [array(), false, array()],
            ['a',       true, 'a   '],
            ['abcd',    true, 'abcd'],
            ['abcdefg', true, 'abcdefg'],
        ];
    }
}
