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

use Aura\Filter\Rule\AbstractDateTime;

/**
 *
 * Sanitize a datetime to a specified format.
 *
 * @package Aura.Filter
 *
 */
class DateTime extends AbstractDateTime
{
    /**
     *
     * Sanitize a datetime to a specified format, default "Y-m-d H:i:s".
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param string $format The date format to use.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke(object $subject, string $field, $format = 'Y-m-d H:i:s'): bool
    {
        $value = $subject->$field;
        $datetime = $this->newDateTime($value);
        if (! $datetime) {
            return false;
        }
        $subject->$field = $datetime->format($format);
        return true;
    }
}
