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
}
