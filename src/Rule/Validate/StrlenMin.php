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
 * Validates that a value is no longer than a certain length.
 *
 * @package Aura.Filter
 *
 */
class StrlenMin extends AbstractStrlen
{
    /**
     *
     * Validates that a value is no longer than a certain length.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $min The value must have at least this many characters.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, $min)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return $this->strlen($value) >= $min;
    }
}
