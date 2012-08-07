<?php
namespace Aura\Filter\Rule;

class MaxTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_MAX';
    
    protected $max = 3;
    
    public function ruleIs($rule)
    {
        return $rule->is($this->max);
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->max);
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->max);
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix($this->max);
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->max);
    }
    
    public function providerIs()
    {
        return [
            [1],
            [2],
            [3],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [array()],
            [4],
            [5],
            [6],
        ];
    }
    
    public function providerFix()
    {
        return [
            [array(), array()],
            [1, 1],
            [2, 2],
            [3, 3],
            [4, 3],
            [5, 3],
            [6, 3],
        ];
    }
}
