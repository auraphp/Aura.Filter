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
 * An abstract input class; serves as the base for Fields, Fieldsets, and
 * Collections.
 * 
 * @package Aura.Input
 * 
 */
abstract class AbstractInput
{
    /**
     * 
     * The name for the input.
     * 
     * @var string
     * 
     */
    protected $name;
    
    /**
     * 
     * The prefix for the name, typically composed of the parent input names.
     * 
     * @var string
     * 
     */
    protected $name_prefix;
    
    /**
     * 
     * Sets the name for the input.
     * 
     * @param string $name The input name.
     * 
     * @return self
     * 
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * 
     * Sets the name prefix for the input, typically composed of the parent
     * input names.
     * 
     * @param string $name_prefix The name prefix.
     * 
     * @return self
     * 
     */
    public function setNamePrefix($name_prefix)
    {
        $this->name_prefix = $name_prefix;
        return $this;
    }
    
    /**
     * 
     * Returns the full name for this input, incuding the prefix (if any).
     * 
     * @return string
     * 
     */
    public function getFullName()
    {
        $name = $this->name;
        if ($this->name_prefix) {
            $name = $this->name_prefix . "[{$name}]";
        }
        return $name;
    }
    
    /**
     * 
     * Support for this input when addressed via Fieldset::__get().
     * 
     * @return self
     * 
     */
    public function read()
    {
        return $this;
    }
    
    /**
     * 
     * Returns this input for the presentation layer.
     * 
     * @return self
     * 
     */
    public function get()
    {
        return $this;
    }
}
