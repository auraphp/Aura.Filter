<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class RegexTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_REGEX';
    
    protected $expr_validate = '/^[\+\-]?[0-9]+$/';
    
    protected $expr_sanitize = '/[^a-z]/';
    
    public function ruleIs($rule)
    {
        return $rule->is($this->expr_validate);
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->expr_validate);
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->expr_validate);
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix($this->expr_sanitize, '@');
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->expr_sanitize, '@');
    }
    
    public function providerIs()
    {
        return [
            ['+1234567890'],
            [1234567890],
            [-123456789.0],
            [-1234567890],
            ['-123'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [array()],
            [' '],
            [''],
            ['-abc.123'],
            ['123.abc'],
            ['123],456'],
            ['0000123.456000'],
        ];
    }
    
    public function providerFix()
    {
        return [
            [array(), false, array()],
            ['abc 123 ,./', true, 'abc@@@@@@@@'],
        ];
    }
}
