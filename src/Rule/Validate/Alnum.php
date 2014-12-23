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
 * Rule for alphanumeric characters.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Alnum extends AbstractRule
{
    /**
     *
     * Validates that the value is only letters (upper/lower case) and digits.
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

        return ctype_alnum((string) $value);
    }

    /**
     *
     * Strips non-alphanumeric characters from the value.
     *
     * @return bool Always true.
     *
     */
    public function sanitize()
    {
        $this->setValue(preg_replace('/[^a-z0-9]/i', '', $this->getValue()));

        return true;
    }
}