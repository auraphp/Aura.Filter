<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter;

use Aura\Filter\Rule\Locator\Locator;
use Aura\Filter\Rule\Locator\SanitizeLocator;
use Aura\Filter\Rule\Locator\ValidateLocator;

/**
 *
 * A filter for individual values.
 *
 * @package Aura.Filter
 *
 */
class ValueFilter
{
    /**
     *
     * A pesudo-subject to hold the value being filtered.
     *
     * @var object
     *
     */
    protected $subject;

    /**
     *
     * The exception class to use when assert() fails.
     *
     * @var string
     *
     */
    protected $exception_class = 'Aura\Filter\Exception\ValueFailed';

    /**
     *
     * Constructor.
     *
     * @param ValidateLocator $validate_locator A locator for "validate" rules.
     *
     * @param SanitizeLocator $sanitize_locator A locator for "sanitize" rules.
     *
     * @return self
     *
     */
    public function __construct(
        ValidateLocator $validate_locator,
        SanitizeLocator $sanitize_locator
    ) {
        $this->validate_locator = $validate_locator;
        $this->sanitize_locator = $sanitize_locator;
        $this->subject = (object) array('value' => null);
    }

    /**
     *
     * Sets the exception class to use when assert() fails.
     *
     * @param string $exception_class The exception class to use when filtering
     * fails.
     *
     * @return null
     *
     */
    public function setExceptionClass($exception_class)
    {
        $this->exception_class = $exception_class;
    }

    /**
     *
     * Validates a value using a rule.
     *
     * @param mixed $value The value to validate.
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments to pass to the rule.
     *
     * @return bool True on success, false on failure.
     *
     */
    public function validate($value, $rule)
    {
        $this->subject->value = $value;
        return $this->apply($this->validate_locator, func_get_args());
    }

    /**
     *
     * Sanitized a value in place using a rule.
     *
     * @param mixed $value The value to sanitize.
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments to pass to the rule.
     *
     * @return bool True on success, false on failure.
     *
     */
    public function sanitize(&$value, $rule)
    {
        $this->subject->value =& $value;
        return $this->apply($this->sanitize_locator, func_get_args());
    }

    /**
     *
     * Asserts that a result is true; throws an exception with a message and
     * code if not.
     *
     * @param bool $result A filter result, usually from validate() or sanitize().
     *
     * @param string $message The exception message to use if the assertion fails.
     *
     * @param int $code The exception code to use if the assertion fails.
     *
     * @return null
     *
     */
    public function assert($result, $message, $code = null)
    {
        if (! $result) {
            $exception = $this->exception_class;
            throw new $exception($message, $code);
        }
    }

    /**
     *
     * Applies a rule.
     *
     * @param Locator $locator A rule locator.
     *
     * @param string $args Arugments for the rule.
     *
     * @return bool True on success, false on failure.
     *
     */
    protected function apply(Locator $locator, $args)
    {
        array_shift($args); // remove $value
        $rule = array_shift($args); // remove $rule
        $rule = $locator->get($rule); // create rule object

        array_unshift($args, 'value'); // add field name on $this->subject
        array_unshift($args, $this->subject); // add $this->subject
        return call_user_func_array($rule, $args);
    }
}
