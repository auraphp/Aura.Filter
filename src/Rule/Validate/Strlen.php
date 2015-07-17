<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
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
class Strlen extends AbstractStrlen
{
    /**
     *
     * Validates that the length of the value is within a given range.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $len The minimum valid length.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, $len)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return $this->strlen($value) == $len;
    }
}
