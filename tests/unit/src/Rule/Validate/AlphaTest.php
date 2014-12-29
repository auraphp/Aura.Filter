<?php
namespace Aura\Filter\Rule\Validate;

class AlphaTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('alphaonly'),
            array('AlphaOnly'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(' '),
            array(''),
            array(0),
            array(1),
            array(2),
            array(5),
            array('0'),
            array('1'),
            array('2'),
            array('5'),
            array("Seven 8 nine"),
            array("non:alpha-numeric's"),
            array('someThing8else'),
            array(array()),
        );
    }
}
