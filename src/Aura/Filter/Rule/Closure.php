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
use \Closure as PhpClosure;

/**
 * 
 * Rule to apply a callable user function to the data.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Closure extends AbstractRule
{
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_CLOSURE',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_CLOSURE',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_CLOSURE',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_CLOSURE',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_CLOSURE',
    ];

    /**
     * 
     * Validates the value against a closure.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate(PhpClosure $closure)
    {
        $closure = $closure->bindTo($this, get_class($this));
        return $closure();
    }

    /**
     * 
     * Sanitizes a value using a closure.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    public function sanitize(PhpClosure $closure)
    {
        $closure = $closure->bindTo($this, get_class($this));
        return $closure();
    }
}
