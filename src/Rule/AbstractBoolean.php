<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
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
     *
     */
    protected function isTrue($value): bool
    {
        if (! $this->isBoolIsh($value)) {
            return false;
        }

        // trim only expects string
        $value = is_string($value) ? strtolower(trim($value)) : $value;

        return $value === true
            || in_array($value, $this->true);
    }

    /**
     *
     * Is a value false-ish?
     *
     * @param mixed $value The value to check.
     *
     *
     */
    protected function isFalse($value): bool
    {
        if (! $this->isBoolIsh($value)) {
            return false;
        }

        // trim only expects string
        $value = is_string($value) ? strtolower(trim($value)) : $value;

        return $value === false
            || in_array($value, $this->false);
    }

    /**
     *
     * Can the value be checked for true/false-ish-ness?
     *
     * @param mixed $value The value to check.
     *
     *
     */
    protected function isBoolIsh($value): bool
    {
        if (is_string($value) || is_int($value) || is_bool($value)) {
            return true;
        }
        return false;
    }
}
