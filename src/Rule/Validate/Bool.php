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
 * Rule for booleans.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Bool
{
    /**
     *
     * Pseudo-true representations.
     *
     * @var array
     *
     */
    protected $true = array('1', 'on', 'true', 't', 'yes', 'y');

    /**
     *
     * Pseudo-false representations; `null` and empty-string are *not* included.
     *
     * @var array
     *
     */
    protected $false = array('0', 'off', 'false', 'f', 'no', 'n');

    /**
     *
     * Validates that the value is a boolean representation.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($object, $field)
    {
        $value = $object->$field;

        // php boolean
        if ($value === true || $value === false) {
            return true;
        }

        // pseudo-boolean
        $lower  = strtolower(trim($value));
        if (in_array($lower, $this->true, true)) {
            // pseudo-true
            return true;
        } elseif (in_array($lower, $this->false, true)) {
            // pseudo-false
            return true;
        } else {
            // not boolean
            return false;
        }
    }

    /**
     *
     * Forces the value to a boolean.
     *
     * Note that this recognizes $this->true and $this->false values.
     *
     * @return bool Always true.
     *
     */
    public function sanitize($object, $field)
    {
        $value = $object->$field;

        // PHP booleans
        if ($value === true || $value === false) {
            // nothing to fix
            return true;
        }

        // pseudo booleans
        $lower = strtolower(trim($value));
        if (in_array($lower, $this->true)) {
            // matches a pseudo true
            $object->$field = true;
        } elseif (in_array($lower, $this->false)) {
            // matches a pseudo false
            $object->$field = false;
        } else {
            // cast to a boolean
            $object->$field = (bool) $value;
        }

        // done!
        return true;
    }
}
