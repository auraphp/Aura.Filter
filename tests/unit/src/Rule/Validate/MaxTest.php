<?php
namespace Aura\Filter\Rule\Validate;

class MaxTest extends AbstractValidateTest
{
    protected $max = 3;

    protected function getArgs()
    {
        return array($this->max);
    }

    public function providerIs()
    {
        return [
            [1],
            [2],
            [3],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            [4],
            [5],
            [6],
        ];
    }

    public function providerFix()
    {
        return [
            [array(), false, array()],
            [1, true, 1],
            [2, true, 2],
            [3, true, 3],
            [4, true, 3],
            [5, true, 3],
            [6, true, 3],
        ];
    }
}
