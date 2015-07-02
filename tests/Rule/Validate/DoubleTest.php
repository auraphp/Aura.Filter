<?php
namespace Aura\Filter\Rule\Validate;

class DoubleTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array("+123456.7890"),
            array(12345.67890),
            array(-123456789.0),
            array(-123.4567890),
            array('-1.23'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(' '),
            array(''),
            array("-abc.123"),
            array("123.abc"),
            array("123,456"),
            array('00.00123.4560.00'),
        );
    }
}
