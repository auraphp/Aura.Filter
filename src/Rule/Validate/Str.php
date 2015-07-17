<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates that the value can be represented as a string.
 *
 * @package Aura.Filter
 *
 */
class Str
{
    /**
     *
     * Validates that the value can be represented as a string.
     *
     * Essentially, this means any scalar value is valid (no arrays, objects,
     * resources, etc).
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
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
