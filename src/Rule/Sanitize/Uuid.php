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
     * Removes all non hexadecimal values or hyphens to test if it is a valid Uuid.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        //either sanitize to pattern with 32 hexadecimals without hyphens or to
        //groups of 8, 4, 4, 4 and 12 hexadecimals with hyphens in between
        $pattern = '/(^[0-9A-Fa-f]{32}$|^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$)/';
        
        $value = preg_replace('/[^a-f0-9-]/i', '', $subject->$field);
        if (preg_match($pattern, $value) == 1) {
            $subject->$field = $value;
            return true;
        }
        return false;
    }
}
