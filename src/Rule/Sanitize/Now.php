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

/**
 *
 * Force the value to the current time, default format "Y-m-d H:i:s".
 *
 * @package Aura.Filter
 *
 */
class Now
{
    /**
     *
     * Force the value to the current time, default format "Y-m-d H:i:s".
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $format The date format.
     *
     * @return bool Always true.
     *
     */
    public function __invoke(object $subject, string $field, $format = 'Y-m-d H:i:s'): bool
    {
        $subject->$field = date($format);
        return true;
    }
}
