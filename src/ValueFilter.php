<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter;

use Aura\Filter\Rule\Locator\SanitizeLocator;
use Aura\Filter\Rule\Locator\ValidateLocator;

/**
 *
 * A standalone filter for individual values.
 *
 * @package Aura.Filter
 *
 */
class ValueFilter
{
    protected $subject;

    protected $exception_class = 'Aura\Filter\Exception\ValueFailed';

    public function __construct(
        ValidateLocator $validate_locator,
        SanitizeLocator $sanitize_locator
    ) {
        $this->validate_locator = $validate_locator;
        $this->sanitize_locator = $sanitize_locator;
        $this->subject = (object) array('value' => null);
    }

    public function setExceptionClass($exception_class)
    {
        $this->exception_class = $exception_class;
    }

    public function validate($value, $rule)
    {
        $this->subject->value = $value;
        $rule = $this->validate_locator->get($rule);
        return $this->apply($rule, func_get_args());
    }

    public function sanitize(&$value, $rule)
    {
        $this->subject->value =& $value;
        $rule = $this->sanitize_locator->get($rule);
        return $this->apply($rule, func_get_args());
    }

    public function assert($result, $message, $code = null)
    {
        if (! $result) {
            $exception = $this->exception_class;
            throw new $exception($message, $code);
        }
    }

    protected function apply($rule, $args)
    {
        array_shift($args); // remove $value
        array_shift($args); // remove $rule
        array_unshift($args, 'value'); // add field name on $this->subject
        array_unshift($args, $this->subject); // add $this->subject
        return call_user_func_array($rule, $args);
    }
}
