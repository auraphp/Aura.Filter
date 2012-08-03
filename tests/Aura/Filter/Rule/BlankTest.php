<?php
namespace Aura\Filter\Rule;

class BlankTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_BLANK';
    
    public function providerIs()
    {
        return [
            [""],
            [" "],
            ["\t"],
            ["\n"],
            ["\r"],
            [" \t \n \r "],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [0],
            [1],
            ['0'],
            ['1'],
            ["Seven 8 nine"],
            ["non:alpha-numeric's"],
            ['someThing8else'],
        ];
    }
    
    public function providerFix()
    {
        return [
            ["", null],
            [" ", null],
            ["\t", null],
            ["\n", null],
            ["\r", null],
            [" \t \n \r ", null],
        ];
    }
}
