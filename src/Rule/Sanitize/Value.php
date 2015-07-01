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
     * Force the field to another value.
     *
     * @param mixed $subject
     *
     * @param string $field
     *
     * @param mixed $other_value
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
