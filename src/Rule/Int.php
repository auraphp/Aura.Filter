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
 * Sanitizes a value to an integer.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Int extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_INT',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_INT',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_INT',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_INT',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_INT',
    ];

    /**
     *
     * Validates that the value represents an integer.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate()
    {
        $value = $this->getValue();

        if (is_int($value)) {
            return true;
        }

        // otherwise, must be numeric, and must be same as when cast to int
        return is_numeric($value) && $value == (int) $value;
    }

    /**
     *
     * Forces the value to an integer.
     *
     * Attempts to extract a valid integer from the given value, using an
     * algorithm somewhat less naive that "remove all characters that are not
     * '0-9+-'".  The result may not be expected, but it will be a integer.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize()
    {
        $value = $this->getValue();

        // sanitize numerics
        if (is_numeric($value)) {
            // we double-cast here to honor scientific notation.
            // (int) 1E5 == 15, but (int) (float) 1E5 == 100000
            $value = (float) $value;
            $this->setValue((int) $value);

            return true;
        }

        if (! is_string($value)) {
            // cannot sanitize a non-string
            return false;
        }

        // it's a non-numeric string, attempt to extract an integer from it.

        // remove all chars except digit and minus.
        // this removes all + signs; any - sign takes precedence because ...
        //     0 + -1 = -1
        //     0 - +1 = -1
        // ... at least it seems that way to me now.
        $value = preg_replace('/[^0-9-]/', '', $value);

        // remove all trailing minuses
        $value = rtrim($value, '-');

        // remove all minuses not at the front
        $is_negative = ($value[0] == '-');
        $value = str_replace('-', '', $value);
        if ($is_negative) {
            $value = '-' . $value;
        }

        // looks like we're done
        $this->setValue((int) $value);

        return true;
    }
}
