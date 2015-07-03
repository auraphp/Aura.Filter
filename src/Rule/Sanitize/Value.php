<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
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
    public function __invoke($subject, $field, $other_value)
    {
        $subject->$field = $other_value;
        return true;
    }
}
