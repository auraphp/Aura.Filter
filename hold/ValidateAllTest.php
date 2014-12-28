<?php
namespace Aura\Filter\Rule\Validate;

use Aura\Filter\Rule\RuleLocator;

class AllTest extends AbstractValidateTest
{
    protected $list = [
        ['alnum'],
        ['strlen', 4],
    ];

    // protected function newRule($data, $field)
    // {
    //     $rule = parent::newRule($data, $field);
    //     $rule->setRuleLocator(new RuleLocator([
    //         'alnum' => function () { return new \Aura\Filter\Rule\Alnum; },
    //         'strlen' => function () { return new \Aura\Filter\Rule\Strlen; },
    //     ]));

    //     return $rule;
    // }

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
            ['0123'],
            ['abcd'],
            ['01ab'],
        ];
    }

    public function providerIsNot()
    {
        return [
            ['1234abcd'],
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
        // can't fix on "all" rule combinations
        return [
            ['$#% abc () 123 ,./', false, '$#% abc () 123 ,./'],
        ];
    }
}
