<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Spec;

use Aura\Filter\Locator\Locator;
use Exception;
use Closure;

/**
 *
 * A generic rule specification.
 *
 * @package Aura.Filter
 *
 */
class Spec
{
    /**
     * Stop filtering on a field when a rule for that field fails.
     */
    const HARD_RULE = 'HARD_RULE';

    /**
     * Continue filtering on a field even when a rule for that field fails.
     */
    const SOFT_RULE = 'SOFT_RULE';

    /**
     * Stop filtering on all fields when a rule fails.
     */
    const STOP_RULE = 'STOP_RULE';

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
    protected $args = array();

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
    protected $failure_mode = self::HARD_RULE;

    /**
     *
     * The rule locator to use.
     *
     * @var Locator
     *
     */
    protected $locator;

    /**
     *
     * Constructor.
     *
     * @param Locator $locator The "sanitize" rules.
     *
     * @return self
     *
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

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
        $rule = $this->locator->get($this->rule);
        $args = $this->args;
        array_unshift($args, $this->field);
        array_unshift($args, $subject);
        return call_user_func_array($rule, $args);
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
        return $this->setFailureMode(self::SOFT_RULE, $message);
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
        return $this->setFailureMode(self::HARD_RULE, $message);
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
        return $this->setFailureMode(self::STOP_RULE, $message);
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
     * On failure, should the subject filter stop processing all fields?
     *
     * @return bool
     *
     */
    public function isStopRule()
    {
        return $this->failure_mode === self::STOP_RULE;
    }

    /**
     *
     * On failure, should the subject filter stop processing the current field?
     *
     * @return bool
     *
     */
    public function isHardRule()
    {
        return $this->failure_mode === self::HARD_RULE;
    }

    /**
     *
     * On failure, should the subject filter keep processing the current field?
     *
     * @return bool
     *
     */
    public function isSoftRule()
    {
        return $this->failure_mode === self::SOFT_RULE;
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
     * Returns the arguments for this rule specification.
     *
     * @return array
     *
     */
    public function getArgs()
    {
        return $this->args;
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
        return $this->rule . $this->argsToString();
    }

    /**
     *
     * Converts the args to a string.
     *
     * @return string
     *
     */
    protected function argsToString()
    {
        if (! $this->args) {
            return '';
        }

        $vals = array();
        foreach ($this->args as $arg) {
            $vals[] = $this->argToString($arg);
        }
        return '(' . implode(', ', $vals) . ')';
    }

    /**
     *
     * Converts one arg to a string.
     *
     * @param mixed $arg The arg.
     *
     * @return string
     *
     */
    protected function argToString($arg)
    {
        switch (true) {
            case $arg instanceof Closure:
                return '*Closure*';
            case is_object($arg):
                return '*' . get_class($arg) . '*';
            case is_array($arg):
                return '*array*';
            case is_resource($arg):
                return '*resource*';
            case is_null($arg):
                return '*null*';
            default:
                return $arg;
        }
    }

    /**
     *
     * Is the subject field blank?
     *
     * @param mixed $subject The filter subject.
     *
     * @return bool
     *
     */
    protected function subjectFieldIsBlank($subject)
    {
        // the field name
        $field = $this->field;

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
}
