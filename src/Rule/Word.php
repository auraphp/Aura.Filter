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
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_WORD',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_WORD',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_WORD',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_WORD',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_WORD',
    ];

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
    public function validate()
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
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize()
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        $this->setValue(preg_replace('/\W/', '', $value));

        return true;
    }
}
