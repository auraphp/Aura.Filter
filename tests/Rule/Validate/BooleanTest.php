<?php
namespace Aura\Filter\Rule\Validate;

class BooleanTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array(true),
            array('on'),
            array('On'),
            array('ON'),
            array('yes'),
            array('Yes'),
            array('YeS'),
            array('y'),
            array('Y'),
            array('true'),
            array('True'),
            array('TrUe'),
            array('t'),
            array('T'),
            array(1),
            array('1'),
            array(false),
            array('off'),
            array('Off'),
            array('OfF'),
            array('no'),
            array('No'),
            array('NO'),
            array('n'),
            array('N'),
            array('false'),
            array('False'),
            array('FaLsE'),
            array('f'),
            array('F'),
            array(0),
            array('0'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array('nothing'),
            array(123),
            array(array(1)),
        );
    }
}
