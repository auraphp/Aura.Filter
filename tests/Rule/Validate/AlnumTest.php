<?php
namespace Aura\Filter\Rule\Validate;

class AlnumTest extends AbstractValidateTest
{
    public static function providerIs()
    {
        return array(
            // array(0),
            // array(1),
            // array(2),
            // array(5),
            array('0'),
            array('1'),
            array('2'),
            array('5'),
            array('alphaonly'),
            array('AlphaOnLy'),
            array('someThing8else'),
            array('soЗѝЦЯng8else'),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(""),
            array(' '),
            array("Seven 8 nine"),
            array("non:alpha-numeric's"),
            array('ЕФГ35%-№'),
            array(array()),
        );
    }
}
