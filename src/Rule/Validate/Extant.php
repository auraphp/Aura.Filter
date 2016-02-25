<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates that the the subject field exists, even if null.
 *
 * @package Aura.Filter
 *
 */
class Extant
{
    /**
     *
     * Validates that the the subject field exists, even if null.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        if (isset($subject->$field)) {
            return true;
        }

        // still, the property might exist and be null. using property_exists()
        // presumes that we have a non-magic-method object, which may not be the
        // case, so we have this hackish approach.

        // first, turn off error reporting entirely.
        $level = error_reporting(0);

        // now put error_get_last() into known state by addressing a nonexistent
        // variable with an unlikely name.
        $fake = __FILE__ . ':' . __CLASS__;
        $value = $$fake;

        // now get the value of the field and turn error reporting back on
        $value = $subject->$field;
        error_reporting($level);

        // if the last error was on $field, then $field is nonexistent.
        $error = error_get_last();
        $property = substr($error['message'], -1 * strlen($field) - 1);
        return $property !== "\$$field";
    }
}
