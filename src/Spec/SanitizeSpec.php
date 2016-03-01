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
 * A "sanitize" rule specification.
 *
 * @package Aura.Filter
 *
 */
class SanitizeSpec extends Spec
{
    /**
     *
     * If the field is blank, use this as the replacement value.
     *
     * @param mixed
     *
     */
    protected $blank_value;

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
        if (! $this->subjectFieldIsBlank($subject)) {
            return parent::__invoke($subject);
        }

        if (! $this->allow_blank) {
            return false;
        }

        $field = $this->field;
        $subject->$field = $this->blank_value;
        return true;
    }

    /**
     *
     * Sanitize the field using this rule (blank not allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     * @return self
     *
     */
    public function to($rule)
    {
        $this->allow_blank = false;
        return $this->init(func_get_args());
    }

    /**
     *
     * Sanitize the using this rule (blank allowed).
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments for the rule.
     *
     * @return self
     *
     */
    public function toBlankOr($rule)
    {
        $this->allow_blank = true;
        return $this->init(func_get_args());
    }

    /**
     *
     * Use this value for blank fields.
     *
     * @param mixed $blank_value Replace the blank field with this value.
     *
     * @return self
     *
     */
    public function useBlankValue($blank_value)
    {
        $this->allow_blank = true;
        $this->blank_value = $blank_value;
        return $this;
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
        return $this->field . ' should have sanitized to '
             . parent::getDefaultMessage();
    }
}
