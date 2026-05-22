<?php
namespace Aura\Filter\Rule\Validate;

class CallbackTest extends AbstractValidateTest
{
    protected function getArgs()
    {
        return array(function ($subject, $field) {
            return is_bool($subject->$field);
        });
    }

    public static function providerIs()
    {
        return array(
            array(true),
            array(false),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(0),
            array(1),
            array(null),
        );
    }

    public static function providerFix()
    {
        return array(
            array(0, true, false),
            array(1, true, true),
        );
    }
}
