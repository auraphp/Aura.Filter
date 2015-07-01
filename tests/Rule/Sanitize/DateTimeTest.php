<?php
namespace Aura\Filter\Rule\Sanitize;

use DateTime as PhpDateTime;

class DateTimeTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        $dt = new PhpDateTime('Nov 7, 1979, 12:34pm');

        return array(
            array(array(), false, array()),
            array('  ', false, '  '),
            array('abcdefghi', false, 'abcdefghi'),
            array('2012-08-02 17:37:29', true, '2012-08-02 17:37:29'),
            array($dt, true, '1979-11-07 12:34:00'),
        );
    }
}
