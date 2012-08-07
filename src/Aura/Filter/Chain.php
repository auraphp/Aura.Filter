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

/**
 * 
 * Filter chain
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Chain
{
    /**
     * 
     * Array of rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * 
     * Error messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * 
     * Fail stop
     *
     * @var array
     */
    protected $failstop = [];

    /**
     *
     * A RuleLocator object
     * 
     * @var RuleLocator
     */
    protected $rule_locator;

    /**
     * 
     * Constructor
     * 
     * @param RuleLocator $rule_locator
     */
    public function __construct($rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }

    /**
     * add a rule; if it fails, stop processing that field
     * 
     * @param string $field
     * 
     * @param string $method
     * 
     * @param string $name
     * 
     */
    public function add($field, $method, $name)
    {
        $params = func_get_args();
        array_shift($params); // $field
        array_shift($params); // $method
        array_shift($params); // $name
        $this->addRule($field, $method, $name, $params, true);
    }

    /**
     * add a rule; if it fails, keep processing that field anyway
     * 
     * @param string $field
     * 
     * @param string $method
     * 
     * @param string $name
     * 
     */
    public function addContinue($field, $method, $name)
    {
        $params = func_get_args();
        array_shift($params); // $field
        array_shift($params); // $method
        array_shift($params); // $name
        $this->addRule($field, $method, $name, $params, false);
    }

    /**
     * 
     * add a rule
     * 
     * @param string $field
     * 
     * @param string $method
     * 
     * @param string $name
     * 
     * @param string $params
     * 
     * @param string $failstop
     * 
     */
    protected function addRule($field, $method, $name, $params, $failstop)
    {
        $this->rules[] = [
            'field'     => $field,
            'method'    => $method,
            'name'      => $name,
            'params'    => $params,
            'failstop'  => $failstop,
        ];
    }

    /**
     * 
     * Executes the rules
     * 
     * @param array $data
     * 
     * @return boolean
     * 
     * @throws InvalidArgumentException
     */
    public function exec(&$data)
    {
        if (! is_object($data)) {
            throw new InvalidArgumentException;
        }

        $this->messages = [];

        $this->failstop = [];

        foreach ($this->rules as $rule) {

            // the field name
            $field = $rule['field'];

            // if the field has failure messages, and we're supposed to
            // stop on failures, then don't apply further rules for this
            // field.
            $continue = isset($this->messages[$field])
                     && isset($this->failstop[$field]);
            if ($continue) {
                continue;
            }

            $object = $this->rule_locator->get($rule['name']);
            $object->prep($data, $field);

            $method = $rule['method'];
            $params = $rule['params'];
            $passed = call_user_func_array([$object, $method], $params);
            if (! $passed) {
                $this->messages[$field][] = [
                    'field'   => $field,
                    'method'  => $rule['method'],
                    'name'    => $rule['name'],
                    'params'  => $rule['params'],
                    'message' => $object->getMessage(),
                ];
                $this->failstop[$field] = $rule['failstop'];
            }
        }

        if ($this->messages) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 
     * gets the array of messages
     * 
     * @return string
     */
    public function getMessages()
    {
        return $this->messages;
    }
}

