<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class FloatTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_FLOAT';
    
    public function providerIs()
    {
        return [
            ["+123456.7890"],
            [12345.67890],
            [-123456789.0],
            [-123.4567890],
            ['-1.23'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [' '],
            [''],
            ["-abc.123"],
            ["123.abc"],
            ["123,456"],
            ['00.00123.4560.00'],
        ];
    }
    
    public function providerFix()
    {
        return [
            [array(), false, array()], // can't fix
            [123.45, true, 123.45],
            ['abc ... 123.45 ,.../', true, 123.450],
            ['a-bc .1. alkasldjf 23 aslk.45 ,.../', true, -.123450],
            ['1E5', true, 100000.0],
        ];
    }
}
