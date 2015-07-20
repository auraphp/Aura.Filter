<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

use Aura\Filter\Rule\AbstractString;

/**
 *
 * Validates that the value is letters only (upper or lower case).
 *
 * @package Aura.Filter
 *
 */
class Alpha extends AbstractString
{
    /**
     *
     * Validates that the value is letters only (upper or lower case).
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
        return (bool) $this->pregValidate(
            '/^[a-z]+/i',
            '/^[\p{L}]+$/u',
            $subject->$field
        );
    }
}
