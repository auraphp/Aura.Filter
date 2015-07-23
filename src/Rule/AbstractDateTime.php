<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule;

use DateTime;

/**
 *
 * Abstract rule for date-time filters.
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractDateTime
{
    /**
     *
     * Returns a new DateTime object.
     *
     * @param mixed $value The incoming date/time value.
     *
     * @return mixed If the value is already a DateTime then it is returned
     * as-is; if the value is invalid as a date/time then `false` is returned;
     * otherwise, a new DateTime is constructed from the value and returned.
     *
     */
    protected function newDateTime($value)
    {
        if ($value instanceof DateTime) {
            return $value;
        }

        if (! is_scalar($value)) {
            return false;
        }

        if (trim($value) === '') {
            return false;
        }

        $datetime = date_create($value);

        // invalid dates (like 1979-02-29) show up as warnings.
        $errors = DateTime::getLastErrors();
        if ($errors['warnings']) {
            return false;
        }

        // looks OK
        return $datetime;
    }
}
