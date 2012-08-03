<?php
namespace Aura\Filter\Rule;

class BetweenTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_BETWEEN';
    
    protected $min = 4;
    
    protected $max = 6;
    
    public function ruleIs($rule)
    {
        return $rule->is($this->min, $this->max);
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->min, $this->max);
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->min, $this->max);
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix($this->min, $this->max);
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->min, $this->max);
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
            [2],
            [3],
            [7],
            [8],
        ];
    }
    
    public function providerFix()
    {
        return [
            [2, 4],
            [3, 4],
            [4, 4],
            [5, 5],
            [6, 6],
            [7, 6],
            [8, 6],
        ];
    }
}
