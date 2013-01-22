<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class AlnumTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_ALNUM';
    
    public function providerIs()
    {
        return [
            [0],
            [1],
            [2],
            [5],
            ['0'],
            ['1'],
            ['2'],
            ['5'],
            ['alphaonly'],
            ['AlphaOnLy'],
            ['someThing8else'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [""],
            [' '],
            ["Seven 8 nine"],
            ["non:alpha-numeric's"],
            [[]],
        ];
    }
    
    public function providerFix()
    {
        return [
            // value, result, expect
            ['$#% abc () 123 ,./', true, 'abc123'],
        ];
    }
}
