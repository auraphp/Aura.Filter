<?php
namespace Aura\Filter\Rule\Validate;

class MinTest extends AbstractValidateTest
{
    protected $min = 4;

    protected function getArgs()
    {
        return array($this->min);
    }

    public function providerIs()
    {
        return [
            [4],
            [5],
            [6],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            [1],
            [2],
            [3],
        ];
    }
}
