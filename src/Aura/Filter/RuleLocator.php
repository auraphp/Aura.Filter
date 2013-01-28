<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Filter;

/**
 * 
 * A ServiceLocator implementation for loading and retaining rule objects.
 * 
 * @package Aura.Filter
 * 
 */
class RuleLocator
{
    /**
     * 
     * A registry to retain rule objects.
     * 
     * @var array
     * 
     */
    protected $registry;

    /**
     * 
     * Tracks whether or not a registry entry has been converted from a 
     * callable to a helper object.
     * 
     * @var array
     * 
     */
    protected $converted = [];
    
    /**
     * 
     * Constructor.
     * 
     * @param array $registry An array of key-value pairs where the key is the
     * rule name and the value is a callable that returns a rule object.
     * 
     */
    public function __construct(array $registry = [])
    {
        $this->merge($registry);
    }

    /**
     * 
     * Merges a new registry of rules over the old registry; new rules will
     * override old ones.
     * 
     * @param array $registry An array of key-value pairs where the key is the
     * rule name and the value is a callable that returns a rule object.
     * 
     */
    public function merge(array $registry = [])
    {
        foreach ($registry as $name => $spec) {
            $this->set($name, $spec);
        }
    }

    /**
     * 
     * Sets one rule into the registry by name.
     * 
     * @param string $name The rule name.
     * 
     * @param callable $spec A callable that returns a rule object.
     * 
     * @return void
     * 
     */
    public function set($name, callable $spec)
    {
        $this->registry[$name] = $spec;
        $this->converted[$name] = false;
    }

    /**
     * 
     * Gets a rule from the registry by name.
     * 
     * @param string $name The rule to retrieve.
     * 
     * @return AbstractRule A rule object.
     * 
     */
    public function get($name)
    {
        if (! isset($this->registry[$name])) {
            throw new Exception\RuleNotMapped($name);
        }

        if (! $this->converted[$name]) {
            $func = $this->registry[$name];
            $this->registry[$name] = $func();
            $this->converted[$name] = true;
        }

        return $this->registry[$name];
    }
}
