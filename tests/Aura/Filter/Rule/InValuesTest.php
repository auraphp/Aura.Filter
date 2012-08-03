<?php
namespace Aura\Filter\Rule;

class InValuesTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_IN_VALUES';
    
    protected $opts = [
        0      => 'val0',
        1      => 'val1',
        'key0' => 'val3',
        'key1' => 'val4',
        'key2' => 'val5'
    ];
    
    public function ruleIs($rule)
    {
        return $rule->is($this->opts);
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->opts);
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->opts);
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix($this->opts);
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->opts);
    }
    
    public function providerIs()
    {
        return [
            ['val0'],
            ['val1'],
            ['val3'],
            ['val4'],
            ['val5'],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [3],
            [4],
            ['a'],
            ['b'],
            ['c'],
        ];
    }
    
    public function providerFix()
    {
        return [
            ['no-good', 'no-good'], // cannot fix
        ];
    }
}
