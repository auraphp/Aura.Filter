<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Forces the value to an integer.
 *
 * @package Aura.Filter
 *
 */
class Integer
{
    /**
     *
     * Forces the value to an integer.
     *
     * Attempts to extract a valid integer from the given value, using an
     * algorithm somewhat less naive that "remove all characters that are not
     * '0-9+-'".  The result may not be expected, but it will be a integer.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;

        // sanitize numerics
        if (is_numeric($value)) {
            // we double-cast here to honor scientific notation.
            // (int) 1E5 == 15, but (int) (float) 1E5 == 100000
            $value = (float) $value;
            $subject->$field = (int) $value;
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
        $subject->$field = (int) $value;
        return true;
    }
}
