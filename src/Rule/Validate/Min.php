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
 * Validates that a value is greater than or equal to a minimum.
 *
 * @package Aura.Filter
 *
 */
class Min
{
    /**
     *
     * Validates that the value is greater than or equal to a minimum.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param int|float $min The minimum valid value.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke(object $subject, string $field, $min): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return $value >= $min;
    }
}
