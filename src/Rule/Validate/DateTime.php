<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

use Aura\Filter\Rule\AbstractDateTime;

/**
 *
 * Validate that a value can be represented as a date/time.
 *
 * @package Aura.Filter
 *
 */
class DateTime extends AbstractDateTime
{
    /**
     *
     * Validate that a value can be represented as a date/time.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;
        $datetime = $this->newDateTime($value);
        return (bool) $datetime;
    }
}
