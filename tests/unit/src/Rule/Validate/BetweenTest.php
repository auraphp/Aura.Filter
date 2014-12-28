<?php
namespace Aura\Filter\Rule\Validate;

class BetweenTest extends AbstractValidateTest
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
            [2],
            [3],
            [7],
            [8],
        ];
    }
}
