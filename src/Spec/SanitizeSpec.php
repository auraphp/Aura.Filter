<?php
namespace Aura\Filter\Spec;

use Aura\Filter\Rule\Locator\SanitizeLocator;

class SanitizeSpec extends AbstractSpec
{
    public function __construct(SanitizeLocator $rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }

    public function to($rule)
    {
        return $this->init(func_get_args());
    }

    protected function getDefaultMessage()
    {
        return $this->field . ' should have sanitized to '
             . parent::getDefaultMessage();
    }
}
