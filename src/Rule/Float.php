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
 * Rule for floats.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Float extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_FLOAT',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_FLOAT',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_FLOAT',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_FLOAT',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_FLOAT',
    ];

    /**
     *
     * Validates that the value represents a float.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate()
    {
        $value = $this->getValue();

        if (is_float($value)) {
            return true;
        }

        // otherwise, must be numeric, and must be same as when cast to float
        return is_numeric($value) && $value == (float) $value;
    }

    /**
     *
     * Forces the value to a float.
     *
     * Attempts to extract a valid float from the given value, using an
     * algorithm somewhat less naive that "remove all characters that are not
     * '0-9.,eE+-'".  The result may not be expected, but it will be a float.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     * @todo Extract scientific notation from weird strings?
     *
     */
    public function sanitize()
    {
        $value = $this->getValue();

        if (is_float($value)) {
            // already a float, nothing to do
            return true;
        }

        if (is_numeric($value)) {
            // numeric string, cast to a float
            $this->setValue((float) $value);

            return true;
        }

        if (! is_string($value)) {
            // not a string, cannot sanitize
            return false;
        }

        // it's a non-numeric string, attempt to extract a float from it.
        // remove all + signs; any - sign takes precedence because ...
        //     0 + -1 = -1
        //     0 - +1 = -1
        // ... at least it seems that way to me now.
        $value = str_replace('+', '', $value);

        // reduce multiple decimals and minuses
        $value = preg_replace('/[\.-]{2,}/', '.', $value);

        // remove all decimals without a digit or minus next to them
        $value = preg_replace('/([^0-9-]\.[^0-9])/', '', $value);

        // remove all chars except digit, decimal, and minus
        $value = preg_replace('/[^0-9\.-]/', '', $value);

        // remove all trailing decimals and minuses
        $value = rtrim($value, '.-');

        // remove all minuses not at the front
        $is_negative = ($value[0] == '-');
        $value = str_replace('-', '', $value);
        if ($is_negative) {
            $value = '-' . $value;
        }

        // remove all decimals but the first
        $pos = strpos($value, '.');
        $value = str_replace('.', '', $value);
        if ($pos !== false) {
            $value = substr($value, 0, $pos)
                   . '.'
                   . substr($value, $pos);
        }

        // done
        $this->setValue((float) $value);

        return true;
    }
}
