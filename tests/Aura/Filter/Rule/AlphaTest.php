<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class AlphaTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_ALPHA';
    
    public function providerIs()
    {
        return [
            ['alphaonly'],
            ['AlphaOnly'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [' '],
            [''],
            [0],
            [1],
            [2],
            [5],
            ['0'],
            ['1'],
            ['2'],
            ['5'],
            ["Seven 8 nine"],
            ["non:alpha-numeric's"],
            ['someThing8else'],
            [[]],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['^&* abc 123 ,./', true, 'abc'],
        ];
    }
}
