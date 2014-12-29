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
    public function __invoke($object, $field)
    {
        $object->$field = preg_replace('/[^a-z0-9]/i', '', $object->$field);
        return true;
    }
}
