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
 * Forces a value to a string, no encoding or escaping.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class String
{
    /**
     *
     * Validates that the value can be represented as a string.
     *
     * Essentially, this means any scalar value is valid (no arrays, objects,
     * resources, etc).
     *
     * @return bool True if valid, false if not.
     *
     * @todo allow for __toString() implementations
     *
     */
    public function __invoke($subject, $field)
    {
        return is_scalar($subject->$field);
    }
}
