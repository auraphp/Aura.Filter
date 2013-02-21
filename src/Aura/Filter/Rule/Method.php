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
 * Rule to call a method on the value object.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Method extends AbstractRule
{
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_METHOD',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_METHOD',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_METHOD',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_METHOD',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_METHOD',
    ];

    /**
     * 
     * Calls a method on the value object to validate itself.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate($method)
    {
        $this->setParams(get_defined_vars());
        return (bool) $this->call(func_get_args());
    }

    /**
     * 
     * Calls a method on the value object to sanitize itself.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    public function sanitize($method)
    {
        $this->setParams(get_defined_vars());
        return (bool) $this->call(func_get_args());
    }
    
    /**
     * 
     * Calls a method on the value object.
     * 
     * @return mixed
     * 
     */
    protected function call(array $args)
    {
        $object = $this->getValue();
        $method = array_shift($args);
        return is_object($object)
            && is_callable([$object, $method])
            && call_user_func_array([$object, $method], $args);
    }
}
