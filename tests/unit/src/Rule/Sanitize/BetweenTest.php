<?php
namespace Aura\Filter\Rule\Sanitize;

class BetweenTest extends AbstractSanitizeTest
{
    protected $min = 4;

    protected $max = 6;

    protected function getArgs()
    {
        $args = parent::getArgs();
        array_push($args, $this->min);
        array_push($args, $this->max);
        return $args;
    }

    public function providerTo()
    {
        return [
            [array(), false, array()],
            [2, true, 4],
            [3, true, 4],
            [4, true, 4],
            [5, true, 5],
            [6, true, 6],
            [7, true, 6],
            [8, true, 6],
        ];
    }
}
