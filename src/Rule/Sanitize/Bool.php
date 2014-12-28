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
namespace Aura\Filter\Rule\Sanitize;

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
     * Forces the value to a boolean.
     *
     * Note that this recognizes $this->true and $this->false values.
     *
     * @return bool Always true.
     *
     */
    public function __invoke($object, $field)
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
