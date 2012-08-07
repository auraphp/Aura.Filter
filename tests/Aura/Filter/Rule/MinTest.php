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
            [array()],
            [1],
            [2],
            [3],
        ];
    }
    
    public function providerFix()
    {
        return [
            [array(), false, array()],
            [1, true, 4],
            [2, true, 4],
            [3, true, 4],
            [4, true, 4],
            [5, true, 5],
            [6, true, 6],
        ];
    }
}
