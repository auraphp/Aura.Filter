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
        return array(
            array(array(), false, array()),
            array(2, true, 4),
            array(3, true, 4),
            array(4, true, 4),
            array(5, true, 5),
            array(6, true, 6),
            array(7, true, 6),
            array(8, true, 6),
        );
    }
}
