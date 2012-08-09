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

use Aura\Filter\AbstractRule;

/**
 * 
 * Sanitizes a value to a string with only word characters.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Word extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_WORD';

    /**
     * 
     * Validates that the value is composed only of word characters.
     * 
     * These include a-z, A-Z, 0-9, and underscore, indicated by a 
     * regular expression "\w".
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate()
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        $expr = '/^\w+$/D';
        return (bool) preg_match($expr, $value);
    }

    /**
     * 
     * Strips non-word characters within the value.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize()
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        $this->setValue(preg_replace('/\W/', '', $value));
        return true;
    }
}
