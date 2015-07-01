<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
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
        $this->allow_blank = false;
        $this->reverse = false;
        return $this->init(func_get_args());
    }

    public function isBlankOr($rule)
    {
        $this->allow_blank = true;
        $this->reverse = false;
        return $this->init(func_get_args());
    }

    public function isNot($rule)
    {
        $this->allow_blank = false;
        $this->reverse = true;
        return $this->init(func_get_args());
    }

    public function isBlankOrNot($rule)
    {
        $this->allow_blank = true;
        $this->reverse = true;
        return $this->init(func_get_args());
    }

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

    protected function applyRule($subject)
    {
        if ($this->reverse) {
            return ! parent::applyRule($subject);
        }

        return parent::applyRule($subject);
    }
}
