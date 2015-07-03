<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

use Aura\Filter\Rule\AbstractDateTime;

/**
 *
 * Sanitize a datetime to a specifed format.
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
    public function __invoke($subject, $field, $format = 'Y-m-d H:i:s')
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
