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

use Aura\Filter\Rule\Locator\SanitizeLocator;

class SanitizeSpec extends AbstractSpec
{
    protected $blank_value;

    public function __construct(SanitizeLocator $rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }

    public function to($rule)
    {
        $this->allow_blank = false;
        return $this->init(func_get_args());
    }

    public function toBlankOr($rule)
    {
        $this->allow_blank = true;
        return $this->init(func_get_args());
    }

    public function useBlankValue($blank_value)
    {
        $this->allow_blank = true;
        $this->blank_value = $blank_value;
    }

    protected function applyBlank($subject, $field)
    {
        if (! parent::applyBlank($subject, $field)) {
            return false;
        }

        $subject->$field = $this->blank_value;
        return true;
    }

    protected function getDefaultMessage()
    {
        return $this->field . ' should have sanitized to '
             . parent::getDefaultMessage();
    }
}
