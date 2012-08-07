<?php
namespace Aura\Filter\Rule;

class StrlenMinTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_STRLEN_MIN';
    
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
            ['abcd'],
            ['efghijkl'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [array()],
            ['a'],
            ['ab'],
            ['abc'],
        ];
    }
    
    public function providerFix()
    {
        return [
            [array(), false, array()],
            ['a',       true, 'a   '],
            ['abcd',    true, 'abcd'],
            ['abcdefg', true, 'abcdefg'],
        ];
    }
}
