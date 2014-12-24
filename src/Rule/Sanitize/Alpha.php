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
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Rule for alphabetic characters.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
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
    public function sanitize($object, $field)
    {
        $object->$field = preg_replace('/[^a-z]/i', '', $object->$field);
        return true;
    }
}
