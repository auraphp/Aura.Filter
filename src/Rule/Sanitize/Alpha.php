<?php
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
 * Strips non-alphabetic characters from the value.
 *
 * @package Aura.Filter
 *
 */
class Alpha
{
    /**
     *
     * Strips non-alphabetic characters from the value.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool Always true.
     *
     */
    public function __invoke($subject, $field)
    {
        $subject->$field = preg_replace('/[^\p{L}]/u', '', $subject->$field);
        return true;
    }
}
