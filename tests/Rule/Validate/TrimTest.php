<?php
namespace Aura\Filter\Rule\Validate;

class TrimTest extends AbstractValidateTest
{
    public static function providerIs()
    {
        return array(
            array('abc'),
            array('абв'),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(array()),
            array(' abc '),
            array(' абв '),
        );
    }
}
