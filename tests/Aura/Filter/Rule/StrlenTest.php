<?php
namespace Aura\Filter\Rule;

class StrlenTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_STRLEN';
    
    protected $len = 4;
    
    public function ruleIs($rule)
    {
        return $rule->is($this->len);
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->len);
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->len);
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix($this->len);
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->len);
    }
    
    public function providerIs()
    {
        return [
            ['abcd'],
            ['efgh'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [array()],
            ['abc'],
            ['defgh'],
        ];
    }
    
    public function providerFix()
    {
        return [
            [array(), array()],
            ['a', 'a   '],
            ['abcd', 'abcd'],
            ['abcdef', 'abcd'],
        ];
    }
}
