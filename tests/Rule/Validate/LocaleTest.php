<?php
namespace Aura\Filter\Rule\Validate;

class LocaleTest extends AbstractValidateTest
{
    public static function providerIs()
    {
        return array(
            array('en_US'),
            array('pt_BR'),
            array('af_ZA'),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(""),
            array(' '),
            array('en_us'),
            array("Seven 8 nine"),
            array("non:alpha-numeric's"),
            array(array()),
        );
    }
}
