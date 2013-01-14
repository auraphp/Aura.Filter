<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;
use Aura\Filter\RuleLocator;

class AnyTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_ANY';
    
    protected $list = [
        // alphanumeric
        ['alnum'],
        // only @ signs
        ['regex', '/^[@]+$/']
    ];
    
    protected function newRule($data, $field)
    {
        $rule = parent::newRule($data, $field);
        $rule->setRuleLocator(new RuleLocator([
            'alnum' => function () { return new \Aura\Filter\Rule\Alnum; },
            'regex' => function () { return new \Aura\Filter\Rule\Regex; },
        ]));
        return $rule;
    }
    
    public function ruleIs($rule)
    {
        return $rule->is($this->list);
    }
    
    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->list);
    }
    
    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->list);
    }
    
    public function ruleFix($rule)
    {
        return $rule->fix($this->list);
    }
    
    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->list);
    }
    
    public function providerIs()
    {
        return [
            [0],
            [1],
            [2],
            [5],
            ['0'],
            ['1'],
            ['2'],
            ['5'],
            ['alphaonly'],
            ['AlphaOnLy'],
            ['someThing8else'],
            ["@@@@@"],
        ];
    }
    
    public function providerIsNot()
    {
        return [
            [""],
            [' '],
            ["Seven 8 nine"],
            ["non:alpha-numeric's"],
            [[]],
            ["something @ somewhere.edu"],
            ["the-name.for!you"],
            ["non:alpha@example.com"],
            [""],
            ["\t\n"],
            [" "],
        ];
    }
    
    public function providerFix()
    {
        // can't fix on "any" rule combinations
        return [
            ['$#% abc () 123 ,./', false, '$#% abc () 123 ,./'],
        ];
    }
}
