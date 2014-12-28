<?php
namespace Aura\Filter\Rule\Sanitize;

class StrlenMaxTest extends AbstractSanitizeTest
{
    protected $max = 3;

    protected function getArgs()
    {
        return array($this->max);
    }

    public function providerTo()
    {
        return [
            [array(),   false, array()],
            ['a',       true, 'a'],
            ['abc',     true, 'abc'],
            ['abcd',    true, 'abc'],
            ['abcdefg', true, 'abc'],
        ];
    }
}
