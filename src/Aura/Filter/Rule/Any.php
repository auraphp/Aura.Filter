<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRule;
use Aura\Filter\RuleLocator;

class Any extends AbstractRule
{
    protected $rule_locator;
    
    protected $message = 'FILTER_ANY';
    
    // rule locator should be an new instance, not a shared service
    public function setRuleLocator(RuleLocator $rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }
    
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
    
    // cannot sanitize to "any" of the listed rules
    public function sanitize()
    {
        return false;
    }
}
