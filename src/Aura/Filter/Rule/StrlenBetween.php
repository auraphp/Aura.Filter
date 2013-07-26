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
 * Validates that a value's length is within a given range.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class StrlenBetween extends AbstractRule
{
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_STRLEN_BETWEEN',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_STRLEN_BETWEEN',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_STRLEN_BETWEEN',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_STRLEN_BETWEEN',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_STRLEN_BETWEEN',
    ];

    /**
     * 
     * Validates that the length of the value is within a given range.
     * 
     * @param mixed $min The minimum valid length.
     * 
     * @param mixed $max The maximum valid length.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate($min, $max)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        $len = strlen($value);
        return ($len >= $min && $len <= $max);
    }

    /**
     * 
     * Sanitize
     * 
     * @param int $min
     * 
     * @param int $max
     * 
     * @param string $pad_string
     * 
     * @param constant $pad_type
     * 
     * @return bool True if the value was sanitized, false if not.
     * 
     */
    public function sanitize($min, $max, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if (strlen($value) < $min) {
            $this->setValue(str_pad($value, $min, $pad_string, $pad_type));
        }
        if (strlen($value) > $max) {
            $this->setValue(substr($value, 0, $max));
        }
        return true;
    }
}
