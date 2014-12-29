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
namespace Aura\Filter\Rule\Validate;

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
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;

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
}
