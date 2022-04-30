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

use Aura\Filter\Rule\AbstractStrlen;

/**
 *
 * Sanitizes a string to an exact length by padding or chopping it.
 *
 * @package Aura.Filter
 *
 */
class Strlen extends AbstractStrlen
{
    /**
     *
     * Sanitizes a string to an exact length by padding or chopping it.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param int $len The string length.
     *
     * @param string $pad_string Pad using this string.
     *
     * @param int $pad_type A `STR_PAD_*` constant.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke(object $subject, string $field, $len, string $pad_string = ' ', int $pad_type = STR_PAD_RIGHT): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if ($this->strlen($value) < $len) {
            $subject->$field = $this->strpad($value, $len, $pad_string, $pad_type);
        }
        if ($this->strlen($value) > $len) {
            $subject->$field = $this->substr($value, 0, $len);
        }
        return true;
    }
}
