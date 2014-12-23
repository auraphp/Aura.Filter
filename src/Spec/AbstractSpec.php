<?php
namespace Aura\Filter;

use Exception;

abstract class AbstractSpec
{
    protected $field;
    protected $rule;
    protected $args;
    protected $message;
    protected $allow_blank = false;
    protected $type = Filter::HARD_RULE;
    protected $exception;

    public function __construct(RuleLocator $rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }

    public function __invoke($object)
    {
        $this->exception = null;
        try {
            return $this->applyBlank($object, $this->field)
                || $this->applyRule($object);
        } catch (Exception $e) {
            $this->exception = $exception;
            return false;
        }
    }

    public function field($field)
    {
        $this->field = $field;
    }

    protected function init($args)
    {
        $this->args = $args;
        $this->rule = array_shift($this->args);
        return $this;
    }

    public function allowBlank($allow_blank = true)
    {
        $this->allow_blank = (bool) $allow_blank;
        return $this;
    }

    public function asSoftRule($message)
    {
        $this->type = Filter::SOFT_RULE;
        $this->message = $message;
    }

    public function asHardRule($message)
    {
        $this->type = Filter::HARD_RULE;
        $this->message = $message;
    }

    public function asStopRule($message)
    {
        $this->type = Filter::STOP_RULE;
        $this->message = $message;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getMessage()
    {
        $message = $this->message;

        if (! $message) {
            $message = $this->getDefaultMessage();
        }

        if ($this->exception) {
            $message .= PHP_EOL . $this->exception->getMessage();
        }

        return $message;
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
        array_shift($this->field);
        array_shift($object);
        return call_user_func_array($rule, $args);
    }
}
