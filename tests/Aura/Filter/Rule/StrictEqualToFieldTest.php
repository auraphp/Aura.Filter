<?php
namespace Aura\Filter\Rule;

class StrictEqualToFieldTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_STRICT_EQUAL_TO_FIELD';
    
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
