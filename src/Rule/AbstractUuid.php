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
     *
     */
    protected function isCanonical(string $value): bool
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
     *
     */
    protected function isHexOnly(string $value): bool
    {
        $regex = '/^[a-f0-9]{32}$/i';
        return (bool) preg_match($regex, $value);
    }
}
