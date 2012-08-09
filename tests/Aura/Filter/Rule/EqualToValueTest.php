<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class EqualToValueTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_EQUAL_TO_VALUE';
    
    protected $other_value = '1';
    
    public function ruleIs($rule)
    {
        return $rule->is($this->other_value);
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->other_value);
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->other_value);
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix($this->other_value);
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->other_value);
    }
    
    public function providerIs()
    {
        return [
            [1],
            ['1'],
            [true],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [0],
            ['2'],
            [false],
        ];
    }
    
    public function providerFix()
    {
        return [
            [0,         true, '1'],
            [1,         true, '1'],
            ['1',       true, '1'],
            [true,      true, '1'],
            [false,     true, '1'],
        ];
    }
}
