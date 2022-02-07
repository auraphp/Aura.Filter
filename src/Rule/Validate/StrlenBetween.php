<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Validate;

use Aura\Filter\Rule\AbstractStrlen;

/**
 *
 * Validates that the length of the value is within a given range.
 *
 * @package Aura.Filter
 *
 */
class StrlenBetween extends AbstractStrlen
{
    /**
     *
     * Validates that the length of the value is within a given range.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $min The minimum valid length.
     *
     * @param mixed $max The maximum valid length.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, $min, $max)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        $len = $this->strlen($value);

        return ($len >= $min && $len <= $max);
    }
}
