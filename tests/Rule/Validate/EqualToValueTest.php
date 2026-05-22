<?php
namespace Aura\Filter\Rule\Validate;

class EqualToValueTest extends AbstractValidateTest
{
    protected $other_value = '1';

    protected function getArgs()
    {
        $args = parent::getArgs();
        $args[] = $this->other_value;
        return $args;
    }

    public static function providerIs()
    {
        return array(
            array(1),
            array('1'),
            array(true),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(0),
            array('2'),
            array(false),
        );
    }
}
