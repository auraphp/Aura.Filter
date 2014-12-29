<?php
namespace Aura\Filter\Rule\Validate;

use DateTime as PhpDateTime;

class DateTimeTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('Nov 7, 1979, 12:34pm'),
            array('0001-01-01 00:00:00'),
            array('1970-08-08 12:34:56'),
            array('2004-02-29 12:00:00'),
            array('0000-01-01T12:34:56'),
            array('1979-11-07T12:34'),
            array('1970-08-08t12:34:56'),
            array('12:00:00'),
            array('9999-12-31'),
            array(new PhpDateTime()),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(' '),
            array(''),
            array(array()),
            array('0000-00-00T00:00:00'),
            array('0010-20-40T12:34:56'),
            array('9999.12:31 ab:cd:ef'),
            array('1979-02-29'),
        );
    }
}
