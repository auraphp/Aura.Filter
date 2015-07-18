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
 * Sanitizes a string to a length range by padding or chopping it.
 *
 * @package Aura.Filter
 *
 */
class StrlenBetween extends AbstractStrlen
{
    /**
     *
     * Sanitizes a string to a length range by padding or chopping it.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param int $min The minimum length.
     *
     * @param int $max The maximum length.
     *
     * @param string $pad_string Pad using this string.
     *
     * @param int $pad_type A `STR_PAD_*` constant.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field, $min, $max, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if ($this->strlen($value) < $min) {
            $subject->$field = $this->strpad($value, $min, $pad_string, $pad_type);
        }
        if ($this->strlen($value) > $max) {
            $subject->$field = $this->substr($value, 0, $max);
        }
        return true;
    }
}
