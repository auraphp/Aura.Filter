<?php
namespace Aura\Filter\Rule\Validate;

class ClosureTest extends AbstractValidateTest
{
    protected function setUp()
    {
        parent::setUp();

        // validates a value as an actual boolean
        $this->validate_closure = function () {
            return is_bool($this->getValue());
        };

        // sanitizes a value to an actual boolean
        $this->sanitize_closure = function () {
            $value = (bool) $this->getValue();
            $this->setValue($value);

            return true;
        };
    }

    public function ruleIs($rule)
    {
        return $rule->is($this->validate_closure);
    }

    public function ruleIsNot($rule)
    {
        return $rule->isNot($this->validate_closure);
    }

    public function ruleIsBlankOr($rule)
    {
        return $rule->isBlankOr($this->validate_closure);
    }

    public function ruleFix($rule)
    {
        return $rule->fix($this->sanitize_closure);
    }

    public function ruleFixBlankOr($rule)
    {
        return $rule->fixBlankOr($this->sanitize_closure);
    }

    public function providerIs()
    {
        return [
            [true],
            [false],
        ];
    }

    public function providerIsNot()
    {
        return [
            [0],
            [1],
            [null],
        ];
    }

    public function providerFix()
    {
        return [
            [0, true, false],
            [1, true, true],
        ];
    }
}
