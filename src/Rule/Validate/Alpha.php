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
namespace Aura\Filter\Rule;

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
     * Validates that the value is letters only (upper or lower case).
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate()
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }

        return ctype_alpha($value);
    }

    /**
     *
     * Strips non-alphabetic characters from the value.
     *
     * @return bool Always true.
     *
     */
    public function sanitize()
    {
        $this->setValue(preg_replace('/[^a-z]/i', '', $this->getValue()));

        return true;
    }
}
