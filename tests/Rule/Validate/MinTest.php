<?php
namespace Aura\Filter\Rule\Validate;

class MinTest extends AbstractValidateTest
{
    protected $min = 4;

    protected function getArgs()
    {
        return array($this->min);
    }

    public static function providerIs()
    {
        return array(
            array(4),
            array(5),
            array(6),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(array()),
            array(1),
            array(2),
            array(3),
        );
    }
}
