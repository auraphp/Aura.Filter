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
        return array(
            array(array(), false, array()),
            array(1, true, 1),
            array(2, true, 2),
            array(3, true, 3),
            array(4, true, 3),
            array(5, true, 3),
            array(6, true, 3),
        );
    }
}
