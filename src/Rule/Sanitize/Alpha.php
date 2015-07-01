<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Rule for alphabetic characters.
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
     * @return bool Always true.
     *
     */
    public function __invoke($subject, $field)
    {
        $subject->$field = preg_replace('/[^a-z]/i', '', $subject->$field);
        return true;
    }
}
