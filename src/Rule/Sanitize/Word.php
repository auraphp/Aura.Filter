<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

use Aura\Filter\Rule\AbstractStrlen;

/**
 *
 * Strips non-word characters within the value.
 *
 * @package Aura.Filter
 *
 */
class Word
{
    /**
     *
     * Strips non-word characters within the value (letters, numbers, and
     * underscores).
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
        $subject->$field = preg_replace('/[^\p{L}\p{Nd}_]/u', '', $value);
        return true;
    }
}
