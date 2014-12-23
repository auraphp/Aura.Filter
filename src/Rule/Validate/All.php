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

use Aura\Filter\Rule\RuleLocator;

/**
 *
 * A "meta-rule" indicating that the value must pass all of a series of rules.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class All
{
    /**
     *
     * A rule locator for rules used by this rule.
     *
     * @param RuleLocator
     *
     */
    protected $rule_locator;

    /**
     *
     * Sets the rule locator; this should be an new instance, not a shared
     * service.
     *
     * @param RuleLocator $rule_locator The rule locator.
     *
     * @return void
     *
     */
    public function setRuleLocator(RuleLocator $rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }

    /**
     *
     * Validates that the value passes all of the rules in a list.
     *
     * @param array $list The list of rules that the value must pass.
     *
     * @return bool True if valid, false if not.
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
            if (! $pass) {
                return false;
            }
        }

        // passed all of the rules in the list
        return true;
    }
}
