<?php
namespace Aura\Filter\Rule\Sanitize;

class StrlenBetweenTest extends AbstractSanitizeTest
{
    protected $min = 4;

    protected $max = 6;

    protected function getArgs()
    {
        return array($this->min, $this->max);
    }

    public function providerTo()
    {
        return [
            [array(), false, array()],
            ['abc',         true, 'abc '],
            ['abcd',        true, 'abcd'],
            ['abcde',       true, 'abcde'],
            ['abcdef',      true, 'abcdef'],
            ['abcdefg',     true, 'abcdef'],
            ['abcdefgh',    true, 'abcdef'],
        ];
    }
}
