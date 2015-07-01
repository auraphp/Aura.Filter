<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Modifies the field value to match the current time, default format "Y-m-d H:i:s".
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Now
{
    /**
     *
     * Force the field to the current time, default format "Y-m-d H:i:s".
     *
     * @param mixed $subject
     *
     * @param string $field
     *
     * @param mixed $other_value
     *
     * @return bool Always true.
     *
     */
    public function __invoke($subject, $field, $format = 'Y-m-d H:i:s')
    {
        $subject->$field = date($format);
        return true;
    }
}
