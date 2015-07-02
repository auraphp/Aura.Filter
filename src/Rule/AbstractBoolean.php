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
 * Abstract rule for boolean filters.
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractBoolean
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
     * Is a value true-ish?
     *
     * @param mixed $value The value to check.
     *
     * @return bool
     *
     */
    protected function isTrue($value)
    {
        if (! $this->isBoolIsh($value)) {
            return false;
        }
        return $value === true
            || in_array(strtolower(trim($value)), $this->true);
    }

    /**
     *
     * Is a value false-ish?
     *
     * @param mixed $value The value to check.
     *
     * @return bool
     *
     */
    protected function isFalse($value)
    {
        if (! $this->isBoolIsh($value)) {
            return false;
        }
        return $value === false
            || in_array(strtolower(trim($value)), $this->false);
    }

    /**
     *
     * Can the value be checked for true/false-ish-ness?
     *
     * @param mixed $value The value to check.
     *
     * @return bool
     *
     */
    protected function isBoolIsh($value)
    {
        if (is_string($value) || is_int($value) || is_bool($value)) {
            return true;
        }
        return false;
    }
}
