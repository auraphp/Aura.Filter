<?php
namespace Aura\Filter\Rule\Validate;

class BlankTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array(null),
            array(""),
            array(" "),
            array("\t"),
            array("\n"),
            array("\r"),
            array(" \t \n \r "),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(0),
            array(1),
            array('0'),
            array('1'),
            array("Seven 8 nine"),
            array("non:alpha-numeric's"),
            array('someThing8else'),
        );
    }
}
