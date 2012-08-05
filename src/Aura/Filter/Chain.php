<?php
namespace Aura\Filter;

class Chain
{
    protected $rules = [];
    
    protected $messages = [];
    
    protected $failstop = [];
    
    protected $rule_locator;
    
    public function __construct($rule_locator)
    {
        $this->rule_locator = $rule_locator;
    }
    
    // add a rule; if it fails, stop processing that field
    public function add($field, $method, $name)
    {
        $params = func_get_args();
        array_shift($params); // $field
        array_shift($params); // $method
        array_shift($params); // $name
        $this->addRule($field, $method, $name, $params, true);
    }
    
    // add a rule; if it fails, keep processing that field anyway
    public function addContinue($field, $method, $name)
    {
        $params = func_get_args();
        array_shift($params); // $field
        array_shift($params); // $method
        array_shift($params); // $name
        $this->addRule($field, $method, $name, $params, false);
    }
    
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
    
    public function exec($data)
    {
        if (is_array($data)) {
            $object = (object) $data;
            $this->exec($object);
            return (array) $object;
        }
        
        if (! is_object($data)) {
            throw new Exception\UnexpectedDataType;
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
    
    public function getMessages()
    {
        return $this->messages;
    }
}
