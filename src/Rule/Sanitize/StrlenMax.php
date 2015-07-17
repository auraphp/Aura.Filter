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
 * Sanitizes a string to a maximum length by chopping it at the right.
 *
 * @package Aura.Filter
 *
 */
class StrlenMax extends AbstractStrlen
{
    /**
     *
     * Sanitizes a string to a maximum length by chopping it at the right.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param int $max The maximum length.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field, $max)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if ($this->strlen($value) > $max) {
            $subject->$field = $this->substr($value, 0, $max);
        }
        return true;
    }
}
