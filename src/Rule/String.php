<?php
/**
 *
 * This file is part of the Aura project for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRule;

/**
 *
 * Forces a value to a string, no encoding or escaping.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class String extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_STRING',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_STRING',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_STRING',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_STRING',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_STRING',
    ];

    /**
     *
     * Validates that the value can be represented as a string.
     *
     * Essentially, this means any scalar value is valid (no arrays, objects,
     * resources, etc).
     *
     * @return bool True if valid, false if not.
     *
     * @todo allow for __toString() implementations
     *
     */
    public function validate()
    {
        return is_scalar($this->getValue());
    }

    /**
     *
     * Forces the value to a string, optionally applying str_replace().
     *
     * @param string $find
     *
     * @param string $replace
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($find = null, $replace = null)
    {
        $this->setParams(get_defined_vars());
        $value = (string) $this->getValue();
        if ($find || $replace) {
            $value = str_replace($find, $replace, $value);
        }
        $this->setValue($value);

        return true;
    }
}
