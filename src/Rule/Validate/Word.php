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
 * Validates that the value is composed only of word characters.
 *
 * @package Aura.Filter
 *
 */
class Word extends AbstractString
{
    /**
     *
     * Validates that the value is composed only of word characters (letters,
     * numbers, and underscores).
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
            '/^[a-z0-9_]$/i',
            '/^[\p{L}\p{Nd}_]+$/u',
            $subject->$field
        );
    }
}
