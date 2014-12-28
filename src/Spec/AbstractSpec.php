<?php
namespace Aura\Filter\Spec;

use Exception;
use Aura\Filter\Rule\RuleLocator;
use Aura\Filter\Filter;

abstract class AbstractSpec
{
    protected $field;
    protected $rule;
    protected $args;
    protected $message;
    protected $allow_blank = false;
    protected $failure_mode = Filter::HARD_RULE;
    protected $rule_locator;

    public function __invoke($object)
    {
        return $this->applyBlank($object, $this->field)
            || $this->applyRule($object);
    }

    public function field($field)
    {
        $this->field = $field;
        return $this;
    }

    public function allowBlank($allow_blank = true)
    {
        $this->allow_blank = (bool) $allow_blank;
        return $this;
    }

    public function asSoftRule($message = null)
    {
        $this->setFailureMode(Filter::SOFT_RULE, $message);
    }

    public function asHardRule($message = null)
    {
        $this->setFailureMode(Filter::HARD_RULE, $message);
    }

    public function asStopRule($message = null)
    {
        $this->setFailureMode(Filter::STOP_RULE, $message);
    }

    protected function setFailureMode($failure_mode, $message)
    {
        $this->failure_mode = $failure_mode;
        if ($message) {
            $this->setMessage($message);
        }
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getFailureMode()
    {
        return $this->failure_mode;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getMessage()
    {
        if (! $this->message) {
            $this->message = $this->getDefaultMessage();
        }
        return $this->message;
    }

    protected function init($args)
    {
        $this->args = $args;
        $this->rule = array_shift($this->args);
        return $this;
    }

    protected function getDefaultMessage()
    {
        $message = $this->rule;
        if ($this->args) {
            $message .= '(' . implode(', ', $this->args) . ')';
        }
        return $message;
    }

    protected function applyBlank($object, $field)
    {
        if (! $this->allow_blank) {
            return false;
        }

        // not set, or null, means it is blank
        if (! isset($object->$field) || $object->$field === null) {
            return true;
        }

        // non-strings are not blank: int, float, object, array, resource, etc
        if (! is_string($object->$field)) {
            return false;
        }

        // strings that trim down to exactly nothing are blank
        return trim($object->$field) === '';
    }

    protected function applyRule($object)
    {
        $rule = $this->rule_locator->get($this->rule);
        $args = $this->args;
        array_unshift($args, $this->field);
        array_unshift($args, $object);
        return call_user_func_array($rule, $args);
    }
}
