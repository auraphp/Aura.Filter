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
            [array()],
            [' abc '],
        ];
    }
    
    public function providerFix()
    {
        return [
            [array(), false, array()],
            [' abc ', true, 'abc'],
        ];
    }
}
