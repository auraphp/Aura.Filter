<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

use Aura\Filter\Rule\AbstractCharCase;

/**
 *
 * Validates that the string is all uppercase.
 *
 * @package Aura.Filter
 *
 */
class Uppercase extends AbstractCharCase
{
    /**
     *
     * Validates that the string is uppercase.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return $this->strtoupper($value) == $value;
    }
}
