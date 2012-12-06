<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class LocaleTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_LOCALE';
    
    public function providerIs()
    {
        return [
            ['en_US'],
            ['pt_BR'],
            ['af_ZA'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [""],
            [' '],
            ['en_us'],
            ["Seven 8 nine"],
            ["non:alpha-numeric's"],
            [[]],
        ];
    }
    
    public function providerFix()
    {
        return [
            // value, result, expect
            ['notacode', false, 'notacode'],
        ];
    }
}
