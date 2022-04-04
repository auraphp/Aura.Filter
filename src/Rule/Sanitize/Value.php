<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Modifies the field value to match another value.
 *
 * @package Aura.Filter
 *
 */
class Value
{
    /**
     *
     * Modifies the field value to match another value.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $other_value The value to set.
     *
     * @return bool Always true.
     *
     */
    public function __invoke(object $subject, string $field, $other_value): bool
    {
        $subject->$field = $other_value;
        return true;
    }
}
