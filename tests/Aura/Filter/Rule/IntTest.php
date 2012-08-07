<?php
namespace Aura\Filter\Rule;

class IntTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_INT';
    
    public function providerIs()
    {
        return [
            ["+1234567890"],
            [1234567890],
            [-123456789.0],
            [-1234567890],
            ['-123'],
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
            ['0000123.456000'],
        ];
    }
    
    public function providerFix()
    {
        return [
            [array(), false, array()], // cannot sanitize
            ['abc ... 123.45 ,.../', true, 12345],
            ['a-bc .1. alkasldjf 23 aslk.45 ,.../', true, -12345],
            ['1E5', true, 100000],
        ];
    }
}
