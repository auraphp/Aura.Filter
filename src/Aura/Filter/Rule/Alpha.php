<?php
/**
 *
 * This file is part of the Aura project for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRule;

/**
 *
 * Rule for alphabetic characters.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Alpha extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_ALPHA',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_ALPHA',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_ALPHA',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_ALPHA',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_ALPHA',
    ];

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
