<?php
namespace Aura\Filter\Rule;

class StrlenBetweenTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_STRLEN_BETWEEN';
    
    public function providerIs()
    {
        return [
            [],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['',''],
        ];
    }
}
