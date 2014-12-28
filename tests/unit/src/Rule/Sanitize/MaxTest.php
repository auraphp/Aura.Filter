<?php
namespace Aura\Filter\Rule\Sanitize;

class MaxTest extends AbstractSanitizeTest
{
    protected $max = 3;

    protected function getArgs()
    {
        return array($this->max);
    }

    public function providerTo()
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
