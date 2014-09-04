<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;
use DateTime as PhpDateTime;

class DateTimeTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_DATE_TIME';

    public function providerIs()
    {
        return [
            ['Nov 7, 1979, 12:34pm'],
            ['0001-01-01 00:00:00'],
            ['1970-08-08 12:34:56'],
            ['2004-02-29 12:00:00'],
            ['0000-01-01T12:34:56'],
            ['1979-11-07T12:34'],
            ['1970-08-08t12:34:56'],
            ['12:00:00'],
            ['9999-12-31'],
            [new PhpDateTime()],
        ];
    }

    public function providerIsNot()
    {
        return [
            [' '],
            [''],
            [array()],
            ['0000-00-00T00:00:00'],
            ['0010-20-40T12:34:56'],
            ['9999.12:31 ab:cd:ef'],
            ['1979-02-29'],
        ];
    }

    public function providerFix()
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
