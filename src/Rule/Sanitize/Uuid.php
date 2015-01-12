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
 * Rule for Universally Unique Identifier (UUID).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Uuid
{
    /**
     *
     * Removes all non hexadecimal values to test if it is a valid Uuid.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $pattern = '/^[0-9a-f]{32}/';
        $value = preg_replace('/[^a-f0-9]/', '', $subject->$field);
        if (preg_match($pattern, $value) == 1) {
            $subject->$field = $value;
            return true;
        }
        return false;
    }
}
