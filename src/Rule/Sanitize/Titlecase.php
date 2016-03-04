<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

use Aura\Filter\Rule\AbstractCharCase;

/**
 *
 * Sanitizes a string to title case.
 *
 * @package Aura.Filter
 *
 */
class Titlecase extends AbstractCharCase
{
    /**
     *
     * Sanitizes a string to title case.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        $subject->$field = $this->ucwords($value);
        return true;
    }
}
