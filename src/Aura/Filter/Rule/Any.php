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
use Aura\Filter\RuleLocator;

/**
 * 
 * Check whether it satisfies at-least one rule
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Any extends AbstractRule
{
    /**
     * 
     * RuleLocator object Should be an new instance, not a shared service
     * 
     * @var Aura\Filter\RuleLocator
     * 
     */
    protected $rule_locator;
    
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_ANY',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_ANY',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_ANY',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_ANY',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_ANY',
    ];
    
    /**
     * 
     * Set the Aura\Filter\RuleLocator object
     * 
     * @param RuleLocator $rule_locator Should be an new instance, not a shared service
     * 
     * @return null
     * 
     */
    public function setRuleLocator(RuleLocator $rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }
    
    /**
     * 
     * Check at-least one of the rule satisfies the condition
     * 
     * @param array $list See available rules
     * 
     * @return bool
     * 
     */
    public function validate(array $list)
    {
        foreach ($list as $args) {
            // take the name off the top of the arguments
            $name = array_shift($args);
            
            // get the rule for that name and prep it
            $rule = $this->rule_locator->get($name);
            $rule->prep($this->data, $this->field);
            
            // does the data pass the rule?
            $pass = call_user_func_array([$rule, 'validate'], $args);
            if ($pass) {
                return true;
            }
        }
        
        // failed all of the rules in the list
        return false;
    }
    
    /**
     * 
     * Cannot sanitize to "any" of the listed rules
     * 
     * @return false
     * 
     */
    public function sanitize()
    {
        return false;
    }
}
