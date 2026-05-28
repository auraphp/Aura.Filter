<?php
namespace Aura\Filter\Rule\Validate;

class StrictEqualToValueTest extends AbstractValidateTest
{
    protected $other_value = '1';

    protected function getArgs()
    {
        return array($this->other_value);
    }

    public static function providerIs()
    {
        return array(
            array('1'),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(1),
            array(true),
            array(1.00),
        );
    }
}
