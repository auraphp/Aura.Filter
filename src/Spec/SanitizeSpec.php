<?php
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
        return $this->init(func_get_args());
    }

    public function useBlankValue($blank_value)
    {
        $this->allowBlank();
        $this->blank_value = $blank_value;
    }

    protected function applyBlank($object, $field)
    {
        if (! parent::applyBlank($object, $field)) {
            return false;
        }

        $object->$field = $this->blank_value;
        return true;
    }

    protected function getDefaultMessage()
    {
        return $this->field . ' should have sanitized to '
             . parent::getDefaultMessage();
    }
}
