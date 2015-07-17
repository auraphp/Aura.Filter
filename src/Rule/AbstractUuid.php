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
 * Abstract rule for Universally Unique Identifier (UUID) filters.
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractUuid
{
    /**
     *
     * Does the value match the canonical UUID format?
     *
     * @param string $value The value to be checked.
     *
     * @return bool
     *
     */
    protected function isCanonical($value)
    {
        $regex = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i';
        return (bool) preg_match($regex, $value);
    }

    /**
     *
     * Is the value a hex-only UUID?
     *
     * @param string $value The value to be checked.
     *
     * @return bool
     *
     */
    protected function isHexOnly($value)
    {
        $regex = '/^[a-f0-9]{32}$/i';
        return (bool) preg_match($regex, $value);
    }
}
