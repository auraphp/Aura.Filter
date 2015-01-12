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
namespace Aura\Filter\Rule\Validate;

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
     * Validates that the value is a uuid.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $pattern = 
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';
        if (preg_match($pattern, $subject->$field)) {
            return true;
        }
        return false;
    }
}
