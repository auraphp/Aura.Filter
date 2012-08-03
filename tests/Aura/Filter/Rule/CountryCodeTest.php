<?php
namespace Aura\Filter\Rule;

class CountryCodeTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_COUNTRY_CODE';
    
    public function providerIs()
    {
        return [
            ['AF'],
            ['AX'],
            ['AL'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            ['XX'],
            ['YY'],
            ['ZZ'],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['XX','XX'], // cannot fix
        ];
    }
}
