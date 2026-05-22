<?php
namespace Aura\Filter\Rule\Validate;

class LowercaseTest extends AbstractValidateTest
{
    public static function providerIs()
    {
        return array(
            array('abcd'),
            array('efgh'),
            array('абвв'),
            array('фгег'),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(array()),
            array('aBcd'),
            array('Efgh'),
            array('АБВВ'),
            array('ФГег'),
        );
    }
}
