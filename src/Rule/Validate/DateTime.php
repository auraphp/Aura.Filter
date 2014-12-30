<?php
/**
 *
 * This file is part of the Aura project for PHP.
 *
 * @package Aura.Filter
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
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class DateTime extends AbstractDateTime
{
    /**
     *
     * Validate a datetime value.
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
