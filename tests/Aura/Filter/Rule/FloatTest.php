<?php
namespace Aura\Filter\Rule;

class FloatTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_FLOAT';
    
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
            [array(), array()], // can't fix
            [123.45, 123.45],
            ['abc ... 123.45 ,.../', 123.450],
            ['a-bc .1. alkasldjf 23 aslk.45 ,.../', -.123450],
            ['1E5', 100000.0],
        ];
    }
}
