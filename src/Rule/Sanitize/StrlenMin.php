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
 * Sanitizes a string to a minimum length by padding it.
 *
 * @package Aura.Filter
 *
 */
class StrlenMin extends AbstractStrlen
{
    /**
     *
     * Sanitizes a string to a minimum length by padding it.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param int $min The minimum length.
     *
     * @param string $pad_string Pad using this string.
     *
     * @param int $pad_type A `STR_PAD_*` constant.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke(object $subject, string $field, $min, $pad_string = ' ', $pad_type = STR_PAD_RIGHT): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if ($this->strlen($value) < $min) {
            $subject->$field = $this->strpad($value, $min, $pad_string, $pad_type);
        }
        return true;
    }
}
