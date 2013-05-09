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
 * Validates that a value is greater than or equal to a minimum.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Min extends AbstractRule
{
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_MIN',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_MIN',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_MIN',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_MIN',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_MIN',
    ];

    /**
     * 
     * Validates that the value is greater than or equal to a minimum.
     * 
     * @param mixed $min The minimum valid value.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate($min)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        return $value >= $min;
    }

    /**
     * 
     * check whether the value is less than min, if so set to min
     * 
     * @param int $min
     * 
     * @return boolean
     */
    public function sanitize($min)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if ($value < $min) {
            $this->setValue($min);
        }
        return true;
    }
}
