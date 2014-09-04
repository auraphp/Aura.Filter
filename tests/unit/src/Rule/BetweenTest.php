<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class BetweenTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_BETWEEN';

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
            [array()],
            [2],
            [3],
            [7],
            [8],
        ];
    }

    public function providerFix()
    {
        return [
            [array(), false, array()],
            [2, true, 4],
            [3, true, 4],
            [4, true, 4],
            [5, true, 5],
            [6, true, 6],
            [7, true, 6],
            [8, true, 6],
        ];
    }
}
