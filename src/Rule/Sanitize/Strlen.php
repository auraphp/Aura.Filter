<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Validates that a value's length is within a given range.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Strlen
{
    /**
     *
     * Sanitize to the length given
     *
     * @param int $len
     *
     * @param string $pad_string
     *
     * @param int $pad_type
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field, $len, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if (strlen($value) < $len) {
            $subject->$field = str_pad($value, $len, $pad_string, $pad_type);
        }
        if (strlen($value) > $len) {
            $subject->$field = substr($value, 0, $len);
        }
        return true;
    }
}
