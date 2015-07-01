<?php
/**
 *
 * This file is part of Aura for PHP.
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
 */
abstract class AbstractBool
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

    protected function isTrue($value)
    {
        if (! $this->validateValue($value)) {
            return false;
        }
        return $value === true
            || in_array(strtolower(trim($value)), $this->true);
    }

    protected function isFalse($value)
    {
        if (! $this->validateValue($value)) {
            return false;
        }
        return $value === false
            || in_array(strtolower(trim($value)), $this->false);
    }

    protected function validateValue($value)
    {
        if (is_string($value) || is_int($value) || is_bool($value)) {
            return true;
        }
        return false;
    }
}
