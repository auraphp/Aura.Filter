<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Spec;

use Exception;
use Aura\Filter\Filter;

/**
 *
 * A generic rule specification.
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractSpec
{
    /**
     *
     * The field name to be filtered.
     *
     * @var string
     *
     */
    protected $field;

    /**
     *
     * The rule name to be applied.
     *
     * @var string
     *
     */
    protected $rule;

    /**
     *
     * Arguments to pass to the rule.
     *
     * @var array
     *
     */
    protected $args;

    /**
     *
     * The message to use on failure.
     *
     * @var string
     *
     */
    protected $message;

    /**
     *
     * Allow the field to be blank?
     *
     * @var bool
     *
     */
    protected $allow_blank = false;

    /**
     *
     * The failure mode to use.
     *
     * @var string
     *
     */
    protected $failure_mode = Filter::HARD_RULE;

    /**
     *
     * The rule locator to use.
     *
     * @var AbstractLocator
     *
     */
    protected $rule_locator;

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
        return $this->applyBlank($subject, $this->field)
            || $this->applyRule($subject);
    }

    /**
     *
     * Sets the subject field name.
     *
     * @param string $field The subject field name.
     *
     * @return self
     *
     */
    public function field($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     *
     * Sets this specification as a "soft" rule.
     *
     * @param string $message The failure message.
     *
     * @return self
     *
     */
    public function asSoftRule($message = null)
    {
        return $this->setFailureMode(Filter::SOFT_RULE, $message);
    }

    /**
     *
     * Sets this specification as a "hard" rule.
     *
     * @param string $message The failure message.
     *
     * @return self
     *
     */
    public function asHardRule($message = null)
    {
        return $this->setFailureMode(Filter::HARD_RULE, $message);
    }

    /**
     *
     * Sets this specification as a "stop" rule.
     *
     * @param string $message The failure message.
     *
     * @return self
     *
     */
    public function asStopRule($message = null)
    {
        return $this->setFailureMode(Filter::STOP_RULE, $message);
    }

    /**
     *
     * Sets the failure mode for this rule specification.
     *
     * @param string $failure_mode The failure mode.
     *
     * @param string $message The failure message.
     *
     * @return self
     *
     */
    protected function setFailureMode($failure_mode, $message)
    {
        $this->failure_mode = $failure_mode;
        if ($message) {
            $this->setMessage($message);
        }
        return $this;
    }

    /**
     *
     * Sets the failure message for this rule specification.
     *
     * @param string $message The failure message.
     *
     * @return self
     *
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     *
     * Returns the failure mode for this rule specification.
     *
     * @return string
     *
     */
    public function getFailureMode()
    {
        return $this->failure_mode;
    }

    /**
     *
     * Returns the field name for this rule specification.
     *
     * @return string
     *
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     *
     * Returns the failure message for this rule specification.
     *
     * @return string
     *
     */
    public function getMessage()
    {
        if (! $this->message) {
            $this->message = $this->getDefaultMessage();
        }
        return $this->message;
    }

    /**
     *
     * Initializes this specification.
     *
     * @param array $args Arguments for the rule.
     *
     * @return self
     *
     */
    protected function init($args)
    {
        $this->args = $args;
        $this->rule = array_shift($this->args);
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
        $message = $this->rule;
        if ($this->args) {
            $message .= '(' . implode(', ', $this->args) . ')';
        }
        return $message;
    }

    protected function applyBlank($subject, $field)
    {
        if (! $this->allow_blank) {
            return false;
        }

        // not set, or null, means it is blank
        if (! isset($subject->$field) || $subject->$field === null) {
            return true;
        }

        // non-strings are not blank: int, float, object, array, resource, etc
        if (! is_string($subject->$field)) {
            return false;
        }

        // strings that trim down to exactly nothing are blank
        return trim($subject->$field) === '';
    }

    protected function applyRule($subject)
    {
        $rule = $this->rule_locator->get($this->rule);
        $args = $this->args;
        array_unshift($args, $this->field);
        array_unshift($args, $subject);
        return call_user_func_array($rule, $args);
    }
}
