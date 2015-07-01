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
 * Rule for Universally Unique Identifier (UUID).
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractUuid
{
    protected function isCanonical($value)
    {
        $regex = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i';
        return (bool) preg_match($regex, $value);
    }

    protected function isHexOnly($value)
    {
        $regex = '/^[a-f0-9]{32}$/i';
        return (bool) preg_match($regex, $value);
    }
}
