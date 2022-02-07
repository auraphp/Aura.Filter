<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Validate;

use Aura\Filter\Rule\AbstractCharCase;

/**
 *
 * Validates that the string is all lowercase.
 *
 * @package Aura.Filter
 *
 */
class Lowercase extends AbstractCharCase
{
    /**
     *
     * Validates that the string is lowercase.
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

        return $this->strtolower($value) == $value;
    }
}
