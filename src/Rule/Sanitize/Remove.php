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
 * Removes the field from the subject with unset().
 *
 * @package Aura.Filter
 *
 */
class Remove
{
    /**
     *
     * Removes the field from the subject with unset().
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool Always true.
     *
     */
    public function __invoke(object $subject, string $field): bool
    {
        unset($subject->$field);
        return true;
    }
}
