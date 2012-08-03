<?php
namespace Aura\Filter\Rule;

class TrimTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_TRIM';
    
    public function providerIs()
    {
        return [
            ['abc'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [' abc '],
        ];
    }
    
    public function providerFix()
    {
        return [
            [' abc ','abc'],
        ];
    }
}
