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
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_ALNUM',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_ALNUM',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_ALNUM',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_ALNUM',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_ALNUM',
    ];

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
