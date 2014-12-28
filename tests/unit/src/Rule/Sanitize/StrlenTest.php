<?php
namespace Aura\Filter\Rule\Sanitize;

class StrlenTest extends AbstractSanitizeTest
{
    protected $len = 4;

    protected function getArgs()
    {
        return array($this->len);
    }

    public function providerTo()
    {
        return [
            [array(),   false, array()],
            ['a',       true, 'a   '],
            ['abcd',    true, 'abcd'],
            ['abcdef',  true, 'abcd'],
        ];
    }
}
