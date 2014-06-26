<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class StrlenBetweenTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_STRLEN_BETWEEN';

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
            ['abcd'],
            ['efghi'],
            ['jklmno'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [array()],
            ['abc'],
            ['defghij'],
        ];
    }

    public function providerFix()
    {
        return [
            [array(), false, array()],
            ['abc',         true, 'abc '],
            ['abcd',        true, 'abcd'],
            ['abcde',       true, 'abcde'],
            ['abcdef',      true, 'abcdef'],
            ['abcdefg',     true, 'abcdef'],
            ['abcdefgh',    true, 'abcdef'],
        ];
    }
}
