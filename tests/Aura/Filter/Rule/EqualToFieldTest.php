<?php
namespace Aura\Filter\Rule;

class EqualToFieldTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_EQUAL_TO_FIELD';
    
    protected $other_field = 'other';
    
    protected $other_value = '1';
    
    public function ruleIs($rule)
    {
        return $rule->is($this->other_field);
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->other_field);
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->other_field);
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix($this->other_field);
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->other_field);
    }
    
    public function prepForValidate($value)
    {
        $data = [
            'field' => $value,
            $this->other_field => $this->other_value
        ];
        
        $field = 'field';
        
        return [$data, $field];
    }
    
    public function prepForSanitize($value)
    {
        $data = [
            'field' => $value,
            $this->other_field => $this->other_value
        ];
        
        $field = 'field';
        
        return [$data, $field];
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
            [0, '1'],
            [1, '1'],
            ['1', '1'],
            [true, '1'],
            [false, '1'],
        ];
    }
}
