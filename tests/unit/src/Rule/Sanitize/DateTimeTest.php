<?php
namespace Aura\Filter\Rule\Sanitize;

use DateTime as PhpDateTime;

class DateTimeTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        $dt = new PhpDateTime('Nov 7, 1979, 12:34pm');

        return [
            [array(), false, array()],
            ['abcdefghi', false, 'abcdefghi'],
            ['2012-08-02 17:37:29', true, '2012-08-02 17:37:29'],
            [$dt, true, '1979-11-07 12:34:00'],
        ];
    }
}
