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

use Aura\Filter\Rule\AbstractCharCase;

/**
 *
 * Sanitizes a string to begin with uppercase.
 *
 * @package Aura.Filter
 *
 */
class UppercaseFirst extends AbstractCharCase
{
    /**
     *
     * Sanitizes a string to begin with uppercase.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke(object $subject, string $field): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        $subject->$field = $this->ucfirst($value);
        return true;
    }
}
