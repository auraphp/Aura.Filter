<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates that the the subject field exists, even if null.
 *
 * @package Aura.Filter
 *
 */
class Extant
{
    /**
     *
     * Validates that the the subject field exists, even if null.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        // if we got this far, via ValidateSpec::fieldExists(), then
        // obviously the field exists.
        return true;
    }
}
