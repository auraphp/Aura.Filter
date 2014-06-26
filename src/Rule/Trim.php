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
 * Sanitizes a value to a string using trim().
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Trim extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_TRIM',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_TRIM',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_TRIM',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_TRIM',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_TRIM',
    ];

    /**
     *
     * The characters to strip; same as PHP trim().
     *
     * @var string
     *
     */
    protected $chars = " \t\n\r\0\x0B";

    /**
     *
     * Is the value already trimmed?
     *
     * @param string $chars The characters to strip.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($chars = null)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if (! $chars) {
            $chars = $this->chars;
        }

        return trim($value, $chars) == $value;
    }

    /**
     *
     * Trims characters from the beginning and end of the value.
     *
     * @param string $chars The characters to strip.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($chars = null)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if (! $chars) {
            $chars = $this->chars;
        }
        $this->setValue(trim($value, $chars));

        return true;
    }
}
