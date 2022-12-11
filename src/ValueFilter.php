<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter;

use stdClass;
use Aura\Filter\Locator\Locator;
use Aura\Filter\Locator\SanitizeLocator;
use Aura\Filter\Locator\ValidateLocator;

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
     * A pseudo-subject to hold the value being filtered.
     *
     * @var stdClass
     */
    protected $subject;

    /**
     * @var ValidateLocator
     */
    protected $validate_locator;

    /**
     * @var SanitizeLocator
     */
    protected $sanitize_locator;

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
    public function validate($value, string $rule): bool
    {
        $this->subject->value = $value;
        return $this->apply($this->validate_locator, func_get_args());
    }

    /**
     *
     * Sanitizes a value in place using a rule.
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
    public function sanitize(&$value, string $rule): bool
    {
        $this->subject->value =& $value;
        return $this->apply($this->sanitize_locator, func_get_args());
    }

    /**
     *
     * Applies a rule.
     *
     * @param Locator $locator A rule locator.
     *
     * @param array $args Arguments for the rule.
     *
     * @return bool True on success, false on failure.
     *
     */
    protected function apply(Locator $locator, array $args): bool
    {
        array_shift($args); // remove $value
        $rule = array_shift($args); // remove $rule
        $rule = $locator->get($rule); // create rule object

        array_unshift($args, 'value'); // add field name on $this->subject
        array_unshift($args, $this->subject); // add $this->subject
        return call_user_func_array($rule, $args);
    }
}
