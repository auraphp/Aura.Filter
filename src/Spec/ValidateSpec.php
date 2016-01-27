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
        $this->allow_null = false;
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
        $this->allow_null = false;
        $this->reverse = false;
        return $this->init(func_get_args());
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
    public function isNullOr($rule)
    {
        $this->allow_null = true;
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
        $this->allow_null = false;
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
        $this->allow_null = false;
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
        if ($this->allow_null) {
            $message .= ' have been null or';
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

        $field = $this->field;
        if (! isset($subject->$field)) {
            return false;
        }

        if ($this->reverse) {
            return ! parent::applyRule($subject);
        }

        return parent::applyRule($subject);
    }
}
