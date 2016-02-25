<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Spec;

/**
 *
 * A "validate" rule specification.
 *
 * @package Aura.Filter
 *
 */
class ValidateSpec extends Spec
{
    /**
     *
     * Reverse the rule, so that a "pass" is treated as a "fail".
     *
     * @var bool
     *
     */
    protected $reverse = false;

    /**
     *
     * Validate the field matches this rule (blank not allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     * @return self
     *
     */
    public function is($rule)
    {
        $this->allow_blank = false;
        $this->reverse = false;
        return $this->init(func_get_args());
    }

    /**
     *
     * Validate the field is blank.
     *
     * @return self
     *
     */
    public function isBlank()
    {
        $this->allow_blank = true;
        $this->reverse = false;
        return $this->init(array());
    }

    /**
     *
     * Validate the field matches this rule (blank allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     * @return self
     *
     */
    public function isBlankOr($rule)
    {
        $this->allow_blank = true;
        $this->reverse = false;
        return $this->init(func_get_args());
    }

    /**
     *
     * Validate the field does not match this rule (blank not allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     * @return self
     *
     */
    public function isNot($rule)
    {
        $this->allow_blank = false;
        $this->reverse = true;
        return $this->init(func_get_args());
    }

    /**
     *
     * Validate the field does not match this rule (blank allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     * @return self
     *
     */
    public function isBlankOrNot($rule)
    {
        $this->allow_blank = true;
        $this->reverse = true;
        return $this->init(func_get_args());
    }

    /**
     *
     * Returns the default failure message for this rule specification.
     *
     * @return string
     *
     */
    protected function getDefaultMessage()
    {
        $message = $this->field . ' should';

        if (! $this->rule) {
            return $message . ' have been blank';
        }

        if ($this->allow_blank) {
            $message .= ' have been blank or';
        }

        if ($this->reverse) {
            $message .= ' not';
        }

        return "{$message} have validated as " . parent::getDefaultMessage();
    }

    /**
     *
     * Check if the subject field passes the rule specification.
     *
     * @param mixed $subject The filter subject.
     *
     * @return bool
     *
     */
    protected function applyRule($subject)
    {
        if (! $this->rule) {
            return false;
        }

        if (! $this->fieldExists($subject)) {
            return false;
        }

        if ($this->reverse) {
            return ! parent::applyRule($subject);
        }

        return parent::applyRule($subject);
    }

    /**
     *
     * Does the field exist in the subject, even if it is null?
     *
     * @param mixed $subject The filter subject.
     *
     * @return bool
     *
     */
    protected function fieldExists($subject)
    {
        $field = $this->field;
        if (isset($subject->$field)) {
            return true;
        }

        // still, the property might exist and be null. using property_exists()
        // presumes that we have a non-magic-method object, which may not be the
        // case, so we have this hackish approach.

        // first, turn off error reporting entirely.
        $level = error_reporting(0);

        // now put error_get_last() into known state by addressing a nonexistent
        // variable with an unlikely name.
        $fake = __FILE__ . ':' . __CLASS__;
        $value = $$fake;

        // now get the value of the field and turn error reporting back on
        $value = $subject->$field;
        error_reporting($level);

        // if the last error was on $field, then $field is nonexistent.
        $error = error_get_last();
        $property = substr($error['message'], -1 * strlen($field) - 1);
        return $property !== "\$$field";
    }
}
