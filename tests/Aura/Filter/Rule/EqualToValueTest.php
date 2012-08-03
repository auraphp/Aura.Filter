<?php
namespace Aura\Filter\Rule;

class EqualToValueTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_EQUAL_TO_VALUE';
    
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
