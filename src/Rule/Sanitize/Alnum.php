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
 * Rule for alphanumeric characters.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Alnum
{
    /**
     *
     * Strips non-alphanumeric characters from the value.
     *
     * @return bool Always true.
     *
     */
    public function __invoke($subject, $field)
    {
        $subject->$field = preg_replace('/[^a-z0-9]/i', '', $subject->$field);
        return true;
    }
}
