<?php
namespace Aura\Filter\Rule\Sanitize;

class MinTest extends AbstractSanitizeTest
{
    protected $min = 4;

    protected function getArgs()
    {
        return array($this->min);
    }

    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array(1, true, 4),
            array(2, true, 4),
            array(3, true, 4),
            array(4, true, 4),
            array(5, true, 5),
            array(6, true, 6),
        );
    }
}
