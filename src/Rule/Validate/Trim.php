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

/**
 *
 * Sanitizes a value to a string using trim().
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Trim
{
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
    public function validate($object, $field, $chars = null)
    {
        $value = $object->$field;
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
    public function sanitize($object, $field, $chars = null)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if (! $chars) {
            $chars = $this->chars;
        }
        $object->$field = trim($value, $chars);

        return true;
    }
}
