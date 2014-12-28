<?php
namespace Aura\Filter\Rule\Validate;

class StrlenBetweenTest extends AbstractValidateTest
{
    protected $min = 4;

    protected $max = 6;

    protected function getArgs()
    {
        return array($this->min, $this->max);
    }

    public function providerIs()
    {
        return [
            ['abcd'],
            ['efghi'],
            ['jklmno'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            ['abc'],
            ['defghij'],
        ];
    }
}
