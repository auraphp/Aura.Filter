<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates that a value is less than than or equal to a maximum.
 *
 * @package Aura.Filter
 *
 */
class Max
{
    /**
     *
     * Validates that the value is less than than or equal to a maximum.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $max The maximum valid value.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke(object $subject, string $field, $max): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return $value <= $max;
    }
}
