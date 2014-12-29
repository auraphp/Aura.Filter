<?php
namespace Aura\Filter\Rule\Validate;

class LocaleTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('en_US'),
            array('pt_BR'),
            array('af_ZA'),
        );
    }

    public function providerIsNot()
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
