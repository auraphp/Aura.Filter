<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
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
     * Applies the rule specification to a subject.
     *
     * @param mixed $subject The filter subject.
     *
     * @return bool True on success, false on failure.
     *
     */
    public function __invoke($subject)
    {
        if ($this->subjectFieldIsBlank($subject)) {
            return $this->allow_blank;
        }

        if (! $this->rule) {
            return $this->reverse;
        }

        if ($this->reverse) {
            return ! parent::__invoke($subject);
        }

        return parent::__invoke($subject);
    }

    /**
     *
     * Validate the field matches this rule (blank not allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     *
     */
    public function is(string $rule): Spec
    {
        $this->allow_blank = false;
        $this->reverse = false;
        return $this->init(func_get_args());
    }

    /**
     *
     * Validate the field is blank.
     *
     *
     */
    public function isBlank(): Spec
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
     *
     */
    public function isBlankOr(string $rule): Spec
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
     *
     */
    public function isNot(string $rule): Spec
    {
        $this->allow_blank = false;
        $this->reverse = true;
        return $this->init(func_get_args());
    }

    /**
     *
     * Validate the field is not blank.
     *
     *
     */
    public function isNotBlank(): Spec
    {
        $this->allow_blank = false;
        $this->reverse = true;
        return $this->init(array());
    }

    /**
     *
     * Validate the field does not match this rule (blank allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     *
     */
    public function isBlankOrNot(string $rule): Spec
    {
        $this->allow_blank = true;
        $this->reverse = true;
        return $this->init(func_get_args());
    }

    /**
     *
     * Returns the default failure message for this rule specification.
     *
     *
     */
    protected function getDefaultMessage(): string
    {
        $message = $this->field . ' should';

        if (! $this->rule) {
            return $message
                . (($this->reverse) ? ' not' : '')
                . ' have been blank';
        }

        if ($this->allow_blank) {
            $message .= ' have been blank or';
        }

        if ($this->reverse) {
            $message .= ' not';
        }

        return "{$message} have validated as " . parent::getDefaultMessage();
    }
}
