<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class MaxTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_MAX';
    
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
            [array(), false, array()],
            [1, true, 1],
            [2, true, 2],
            [3, true, 3],
            [4, true, 3],
            [5, true, 3],
            [6, true, 3],
        ];
    }
}
