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
 * Rule for Universally Unique Identifier (UUID).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
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
