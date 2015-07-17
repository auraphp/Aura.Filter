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
        return array(
            array(1),
            array(2),
            array(3),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array(4),
            array(5),
            array(6),
        );
    }
}
