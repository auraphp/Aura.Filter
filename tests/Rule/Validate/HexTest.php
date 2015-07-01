<?php
namespace Aura\Filter\Rule\Validate;

class HexTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('abcdef'),
            array('01234f'),
            array('a1b2c3'),
            array('ffffff'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(""),
            array(' '),
            array("Seven 8 nine"),
            array("non:alpha-numeric's"),
            array(array()),
        );
    }
}
