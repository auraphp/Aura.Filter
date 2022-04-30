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
 * Validates that the value is within a given range.
 *
 * @package Aura.Filter
 *
 */
class Between
{
    /**
     *
     * Validates that the value is within a given range.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param int $min The minimum valid value.
     *
     * @param int $max The maximum valid value.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke(object $subject, string $field, $min, $max): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        return ($value >= $min && $value <= $max);
    }
}
