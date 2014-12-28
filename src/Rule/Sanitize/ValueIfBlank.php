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
class ValueIfBlank
{
    /**
     *
     * Sanitize to another value if blank.
     *
     * @return bool
     *
     */
    public function __invoke($object, $field, $value)
    {
        // if not blank, no problem
        if (! $this->isBlank($object, $field)) {
            return true;
        }

        if ($value instanceof \Closure) {
            return $value($object, $field);
        }

        $object->$field = $value;
        return true;
    }

    protected function isBlank($object, $field)
    {
        // not set, or null, means it is blank
        if (! isset($object->$field) || $object->$field === null) {
            return true;
        }

        // non-strings are not blank: int, float, object, array, resource, etc
        if (! is_string($object->$field)) {
            return false;
        }

        // strings that trim down to exactly nothing are blank
        return trim($object->$field) === '';
    }
}
