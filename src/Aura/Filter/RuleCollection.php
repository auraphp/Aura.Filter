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
namespace Aura\Filter;

use InvalidArgumentException;
use StdClass;

/**
 * 
 * A collection of rules to be applied to a data object.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class RuleCollection
{
    /**
     * Stop filtering on a field when a rule fails.
     */
    const HARD_RULE = 'HARD_RULE';

    /**
     * Continue filtering on a field when a rule fails.
     */
    const SOFT_RULE = 'SOFT_RULE';

    /**
     * Stop filtering on all fields when a rule fails.
     */
    const STOP_RULE = 'STOP_RULE';

    /**
     * Apply a rule to check if the value is valid.
     */
    const IS = 'is';

    /**
     * Apply a rule to check if the value **is not** valid.
     */
    const IS_NOT = 'isNot';

    /**
     * Apply a rule to check if the value is blank **or** is valid.
     */
    const IS_BLANK_OR = 'isBlankOr';

    /**
     * Sanitize the value according to the rule.
     */
    const FIX = 'fix';

    /**
     * Sanitize the value to `null` if blank, or according to the rule if not.
     */
    const FIX_BLANK_OR = 'fixBlankOr';
    
    /**
     * 
     * The rules to be applied to a data object.
     *
     * @var array
     * 
     */
    protected $rules = [];

    /**
     * 
     * Error messages from failed rules.
     *
     * @var array
     * 
     */
    protected $messages = [];

    /**
     * 
     * Fields that have errored on a hard rule.
     *
     * @var array
     * 
     */
    protected $hardrule = [];

    /**
     *
     * A RuleLocator object.
     * 
     * @var RuleLocator
     */
    protected $rule_locator;

    /**
     * 
     * Constructor.
     * 
     * @param RuleLocator $rule_locator
     * 
     */
    public function __construct($rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }

    /**
     * 
     * Add a rule; keep applying all other rules even if it fails.
     * 
     * @param string $field
     * 
     * @param string $method
     * 
     * @param string $name
     * 
     * @return $this
     * 
     */
    public function addSoftRule($field, $method, $name)
    {
        $params = func_get_args();
        array_shift($params); // $field
        array_shift($params); // $method
        array_shift($params); // $name
        $this->addRule($field, $method, $name, $params, self::SOFT_RULE);
        return $this;
    }

    /**
     * 
     * Add a rule; if it fails, stop applying rules on that field.
     * 
     * @param string $field
     * 
     * @param string $method
     * 
     * @param string $name
     * 
     * @return $this
     * 
     */
    public function addHardRule($field, $method, $name)
    {
        $params = func_get_args();
        array_shift($params); // $field
        array_shift($params); // $method
        array_shift($params); // $name
        $this->addRule($field, $method, $name, $params, self::HARD_RULE);
        return $this;
    }

    /**
     * 
     * Add a rule; if it fails, stop applying rules on all remaining data.
     * 
     * @param string $field
     * 
     * @param string $method
     * 
     * @param string $name
     * 
     * @return $this
     * 
     */
    public function addStopRule($field, $method, $name)
    {
        $params = func_get_args();
        array_shift($params); // $field
        array_shift($params); // $method
        array_shift($params); // $name
        $this->addRule($field, $method, $name, $params, self::STOP_RULE);
        return $this;
    }

    /**
     * 
     * Returns the collection of rules to be applied.
     * 
     * @return array
     * 
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Return RuleLocator object
     * 
     * @return RuleLocator
     * 
     */
    public function getRuleLocator()
    {
        return $this->rule_locator;
    }

    /**
     * 
     * Add a rule.
     * 
     * @param string $field
     * 
     * @param string $method
     * 
     * @param string $name
     * 
     * @param string $params
     * 
     * @param string $type The rule type: soft, hard, stop.
     * 
     */
    protected function addRule($field, $method, $name, $params, $type)
    {
        $this->rules[] = [
            'field'     => $field,
            'method'    => $method,
            'name'      => $name,
            'params'    => $params,
            'type'      => $type,
            'applied'   => false,
        ];
    }

    /**
     * 
     * Applies the rules to the field values of a data object or array; note 
     * that sanitizing filters may modify the values in place.
     * 
     * @param object|array &$data The data object or array to be filtered.
     * 
     * @return boolean True if all rules were applied without error; false if
     * there was at least one error.
     * 
     */
    public function values(&$data)
    {
        // convert array to object and recurse
        if (is_array($data)) {
            $object = (object) $data;
            $result = $this->values($object);
            $data = (array) $object;
            return $result;
        }
        
        // must be an object at this point
        if (! is_object($data)) {
            throw new InvalidArgumentException;
        }
        
        // reset messages and hard-rule notices
        $this->messages = [];
        $this->hardrule = [];

        foreach ($this->rules as $i => &$rule) {

            // the field name
            $field = $rule['field'];

            // if we've hit a hard rule for this field, then don't apply
            // further rules on this field.
            if (in_array($field, $this->hardrule)) {
                continue;
            }

            $object = $this->rule_locator->get($rule['name']);
            $object->prep($data, $field);

            $method = $rule['method'];
            $params = $rule['params'];
            $passed = call_user_func_array([$object, $method], $params);
            $rule['applied'] = true;

            if (! $passed) {

                // failed. keep the failure message.
                $this->messages[$field][] = [
                    'field'   => $field,
                    'method'  => $rule['method'],
                    'name'    => $rule['name'],
                    'params'  => $rule['params'],
                    'message' => $object->getMessage(),
                    'type'    => $rule['type'],
                ];

                // should we stop filtering this field?
                if ($rule['type'] == static::HARD_RULE) {
                    $this->hardrule[] = $field;
                }

                // should we stop filtering entirely?
                if ($rule['type'] == static::STOP_RULE) {
                    return false;
                }
            }
        }

        // if there are messages, it's a failure
        return $this->messages ? false : true;
    }

    /**
     * 
     * Returns the array of failure messages.
     * 
     * @return array
     * 
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * 
     * Convenience method to apply a rule directly to an individual value.
     * 
     * @param mixed &$value Apply the rule to this value; the rule may modify
     * the value in place.
     * 
     * @param string $method The rule method to use; e.g., Filter::IS.
     * 
     * @param string $name The of the rule to apply.
     * 
     * @return bool True if the rule was applied successfully, false if not.
     * 
     */
    public function value(&$value, $method, $name)
    {
        // get the params
        $params = func_get_args();
        array_shift($params); // $value
        array_shift($params); // $method
        array_shift($params); // $name
        
        // set up the field name and data
        $field = 'field';
        $data = (object) [$field => $value];
        
        // prep and call the rule object
        $rule = $this->rule_locator->get($name);
        $rule->prep($data, $field);
        $passed = call_user_func_array([$rule, $method], $params);
        
        // retain the value and return the pass/fail result
        $value = $rule->getValue();
        return $passed;
    }
}
