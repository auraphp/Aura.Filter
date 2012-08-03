<?php
namespace Aura\Filter\Rule;

class MinTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_MIN';
    
    protected $min = 4;
    
    public function ruleIs($rule)
    {
        return $rule->is($this->min);
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->min);
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->min);
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix($this->min);
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->min);
    }
    
    public function providerIs()
    {
        return [
            [4],
            [5],
            [6],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [1],
            [2],
            [3],
        ];
    }
    
    public function providerFix()
    {
        return [
            [1, 4],
            [2, 4],
            [3, 4],
            [4, 4],
            [5, 5],
            [6, 6],
        ];
    }
}
