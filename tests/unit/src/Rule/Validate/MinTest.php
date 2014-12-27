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

    public function providerFix()
    {
        return [
            [array(), false, array()],
            [1, true, 4],
            [2, true, 4],
            [3, true, 4],
            [4, true, 4],
            [5, true, 5],
            [6, true, 6],
        ];
    }
}
