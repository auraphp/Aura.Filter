<?php
namespace Aura\Filter\Rule;

class StringTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_STRING';
    
    public function ruleFix($rule)
    {
        $rule->fix(' ', '@');
    }
    
    public function ruleFixBlankOr($rule)
    {
        $rule->fixBlankOr(' ', '@');
    }
    
    public function providerIs()
    {
        return [
            [12345],
            [123.45],
            [true],
            [false],
            ['string'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [array()],
            [new \StdClass],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['abc 123 ,./', 'abc@123@,./'],
            [12345, '12345'],
        ];
    }
}
