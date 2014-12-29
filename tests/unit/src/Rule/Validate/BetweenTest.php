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
        return array(
            array(4),
            array(5),
            array(6),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array(2),
            array(3),
            array(7),
            array(8),
        );
    }
}
