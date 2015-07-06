<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Spec;

use Aura\Filter\Locator\ValidateLocator;

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
     * Constructor.
     *
     * @param ValidateLocator $rule_locator The "validate" rules.
     *
     * @return self
     *
     */
    public function __construct(ValidateLocator $rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }

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
        if ($this->reverse) {
            return ! parent::applyRule($subject);
        }

        return parent::applyRule($subject);
    }
}
