<?php
namespace Aura\Filter\Rule;

class StrlenMaxTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_STRLEN_MAX';
    
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
            ['a'],
            ['ab'],
            ['abc'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [array()],
            ['abcd'],
            ['abcdefg'],
        ];
    }
    
    public function providerFix()
    {
        return [
            [array(), false, array()],
            ['a',       true, 'a'],
            ['abc',     true, 'abc'],
            ['abcd',    true, 'abc'],
            ['abcdefg', true, 'abc'],
        ];
    }
}
