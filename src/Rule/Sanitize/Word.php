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
 * Sanitizes a value to a string with only word characters.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Word
{
    /**
     *
     * Strips non-word characters within the value.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($object, $field)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }
        $object->$field = preg_replace('/\W/', '', $value);
        return true;
    }
}
