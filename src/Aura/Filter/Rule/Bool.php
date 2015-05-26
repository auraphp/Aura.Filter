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
 * Rule for booleans.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Bool extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_BOOL',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_BOOL',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_BOOL',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_BOOL',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_BOOL',
    ];

    /**
     *
     * Pseudo-true representations.
     *
     * @var array
     *
     */
    protected $true = array('1', 'on', 'true', 't', 'yes', 'y');

    /**
     *
     * Pseudo-false representations; `null` and empty-string are *not* included.
     *
     * @var array
     *
     */
    protected $false = array('0', 'off', 'false', 'f', 'no', 'n');

    /**
     *
     * Validates that the value is a boolean representation.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate()
    {
        $value = $this->getValue();

        // php boolean
        if ($value === true || $value === false) {
            return true;
        }

        // pseudo-boolean
        $lower  = strtolower(trim($value));
        if (in_array($lower, $this->true, true)) {
            // pseudo-true
            return true;
        } elseif (in_array($lower, $this->false, true)) {
            // pseudo-false
            return true;
        } else {
            // not boolean
            return false;
        }
    }

    /**
     *
     * Forces the value to a boolean.
     *
     * Note that this recognizes $this->true and $this->false values.
     *
     * @return bool Always true.
     *
     */
    public function sanitize()
    {
        $value = $this->getValue();

        // PHP booleans
        if ($value === true || $value === false) {
            // nothing to fix
            return true;
        }

        // pseudo booleans
        $lower = strtolower(trim($value));
        if (in_array($lower, $this->true)) {
            // matches a pseudo true
            $this->setValue(true);
        } elseif (in_array($lower, $this->false)) {
            // matches a pseudo false
            $this->setValue(false);
        } else {
            // cast to a boolean
            $this->setValue((bool) $value);
        }

        // done!
        return true;
    }
}
