<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class EqualToFieldTest extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_EQUAL_TO_FIELD';

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

    public function getPrep($value)
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
            [0,         true, '1'],
            [1,         true, '1'],
            ['1',       true, '1'],
            [true,      true, '1'],
            [false,     true, '1'],
        ];
    }

    public function testRuleIs_fieldNotSet()
    {
        list($data, $field) = $this->getPrep('foo');
        $rule = $this->newRule($data, $field);
        $this->assertFalse($rule->is('no_such_field'));
    }

    public function testRuleIsNot_fieldNotSet()
    {
        list($data, $field) = $this->getPrep('foo');
        $rule = $this->newRule($data, $field);
        $this->assertTrue($rule->isNot('no_such_field'));
    }

    public function testRuleFix_fieldNotSet()
    {
        list($data, $field) = $this->getPrep('foo');
        $rule = $this->newRule($data, $field);
        $this->assertFalse($rule->fix('no_such_field'));
    }
}
