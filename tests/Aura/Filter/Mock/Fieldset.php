<?php
/**
 * 
 * This file is part of the Aura project for PHP.
 * 
 * @package Aura.Input
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Filter\Mock;

/**
 * 
 * A fieldset of inputs, where the inputs themselves may be values, other
 * fieldsets, or other collections.
 * 
 * @package Aura.Input
 * 
 */
class Fieldset extends AbstractInput
{
    /**
     * 
     * A builder to create input objects.
     * 
     * @var Builder
     * 
     */
    protected $builder;
    
    /**
     * 
     * A filter for the fieldset values.
     * 
     * @var FilterInterface
     * 
     */
    protected $filter;
    
    /**
     * 
     * Inputs in the fieldset.
     * 
     * @var array
     * 
     */
    protected $inputs = [];
    
    /**
     * 
     * Object for retaining information about options available to the form
     * inputs.
     * 
     * @var mixed
     * 
     */
    protected $options;
    
    /**
     * 
     * Constructor.
     * 
     * @param BuilderInterface $builder An object to build input objects.
     * 
     * @param FilterInterface $filter A filter object for this fieldset.
     * 
     * @param object $options An arbitrary options object for use when setting
     * up inputs and filters.
     * 
     */
    public function __construct(
        BuilderInterface $builder,
        FilterInterface  $filter,
        $options = null
    ) {
        $this->builder  = $builder;
        $this->filter   = $filter;
        $this->options  = $options;
        $this->init();
    }
    
    /**
     * 
     * Gets an input value from this fieldset.
     * 
     * @param string $key The input name.
     * 
     * @return mixed The input value.
     * 
     */
    public function __get($key)
    {
        return $this->getInput($key)->read();
    }
    
    /**
     * 
     * Sets an input value on this fieldset.
     * 
     * @param string $key The input name.
     * 
     * @param mixed $val The input value.
     * 
     * @return void
     * 
     */
    public function __set($key, $val)
    {
        $this->getInput($key)->fill($val);
    }
    
    /**
     * 
     * Returns the filter object.
     * 
     * @return FilterInterface
     * 
     */
    public function getFilter()
    {
        return $this->filter;
    }
    
    /**
     * 
     * Returns an individual input object by name.
     * 
     * @param string $name The name of the input object.
     * 
     * @return AbstractInput
     * 
     */
    public function getInput($name)
    {
        if (! isset($this->inputs[$name])) {
            throw new Exception\NoSuchInput($name);
        }
        
        $input = $this->inputs[$name];
        $input->setNamePrefix($this->getFullName());
        return $input;
    }

    /**
     * 
     * Returns the names of all input objects in this fieldset.
     * 
     * @return array
     * 
     */
    public function getInputNames()
    {
        return array_keys($this->inputs);
    }
    
    /**
     * 
     * Returns the input builder.
     * 
     * @return BuilderInterface
     * 
     */
    public function getBuilder()
    {
        return $this->builder;
    }
    
    /**
     * 
     * Returns the options object
     * 
     * @return mixed
     * 
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * 
     * Fills this fieldset with input values.
     * 
     * @param array $data The values for this fieldset.
     * 
     * @return void
     * 
     */
    public function fill(array $data)
    {
        foreach ($this->inputs as $key => $input) {
            if (array_key_exists($key, $data)) {
                $input->fill($data[$key]);
            }
        }
    }
    
    /**
     * 
     * Initializes the inputs and filter.
     * 
     * @return void
     * 
     */
    public function init()
    {
    }
    
    /**
     * 
     * Sets a new Field input.
     * 
     * @param string $name The Field name.
     * 
     * @param string $type A Field of this type; defaults to 'text'.
     * 
     * @return Field
     * 
     */
    public function setField($name, $type = null)
    {        
        $this->inputs[$name] = $this->builder->newField($name, $type);
        return $this->inputs[$name];
    }
    
    /**
     * 
     * Sets a new Fieldset input.
     * 
     * @param string $name The Fieldset name.
     * 
     * @param string $type A Fieldset of this type; defaults to $name.
     * 
     * @return Fieldset
     * 
     */
    public function setFieldset($name, $type = null)
    {        
        $this->inputs[$name] = $this->builder->newFieldset($name, $type);
        return $this->inputs[$name];
    }
    
    /**
     * 
     * Sets a new Collection input.
     * 
     * @param string $name The Collection name.
     * 
     * @param string $type A Collection of this type of Fieldset; defaults to
     * $name.
     * 
     * @return Collection
     * 
     */
    public function setCollection($name, $type = null)
    {        
        $this->inputs[$name] = $this->builder->newCollection($name, $type);
        return $this->inputs[$name];
    }
    
    /**
     * 
     * Returns an input in a format suitable for a view.
     * 
     * @param string $name The input name.
     * 
     * @return mixed
     * 
     */
    public function get($name = null)
    {
        if (! $name) {
            return $this;
        }
        
        if (! isset($this->inputs[$name])) {
            throw new Exception\NoSuchInput($name);
        }
        
        $input = $this->inputs[$name];
        $input->setNamePrefix($this->getFullName());
        return $input->get();
    }
    
    /**
     * 
     * Filters the inputs on this fieldset.
     * 
     * @return bool True if all the filter rules pass, false if not.
     * 
     */
    public function filter()
    {
        return $this->filter->values($this);
    }
    
    /**
     * 
     * Gets the filter messages.
     * 
     * @param string $name The input name to get the filter message for; if
     * empty, gets all messages for all inputs.
     * 
     * @return array The filter messages.
     * 
     */
    public function getMessages($name = null)
    {
        return $this->filter->getMessages($name);
    }
}
