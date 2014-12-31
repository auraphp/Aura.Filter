<?php
namespace Aura\Filter;

use Aura\Filter\Rule\Locator\SanitizeLocator;
use Aura\Filter\Rule\Locator\ValidateLocator;

class ValueFilter
{
    public $value;

    public function __construct(
        ValidateLocator $validate_locator,
        SanitizeLocator $sanitize_locator
    ) {
        $this->validate_locator = $validate_locator;
        $this->sanitize_locator = $sanitize_locator;
    }

    public function validate($value, $rule)
    {
        $this->value = $value;
        $rule = $this->validate_locator->get($rule);
        return $this->apply($rule, func_get_args());
    }

    public function sanitize(&$value, $rule)
    {
        $this->value =& $value;
        $rule = $this->sanitize_locator->get($rule);
        return $this->apply($rule, func_get_args());
    }

    protected function apply($rule, $args)
    {
        array_shift($args); // remove $value
        array_shift($args); // remove $rule
        array_unshift($args, 'value'); // add field name on $this
        array_unshift($args, $this); // add $this as subject
        return call_user_func_array($rule, $args);
    }
}
