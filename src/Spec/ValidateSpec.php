<?php
namespace Aura\Filter\Spec;

use Aura\Filter\Rule\Locator\ValidateLocator;

class ValidateSpec extends AbstractSpec
{
    protected $reverse = false;

    public function __construct(ValidateLocator $rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }

    public function is($rule)
    {
        $this->reverse = false;
        return $this->init(func_get_args());
    }

    public function isNot($rule)
    {
        $this->reverse = true;
        return $this->init(func_get_args());
    }

    protected function getDefaultMessage()
    {
        $message = $this->field . ' should';
        if ($this->reverse) {
            $message .= ' not';
        }
        $message .= ' have validated as ';
        return $message . parent::getDefaultMessage();
    }

    protected function applyRule($object)
    {
        if ($this->reverse) {
            return ! parent::applyRule($object);
        }

        return parent::applyRule($object);
    }
}
