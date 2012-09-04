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
     * Constructor.
     * 
     * @param array $registry An array of key-value pairs where the key is the
     * rule name (doubles as a method name) and the value is the rule
     * object. The value may also be a closure that returns a rule object.
     * Note that is has to be a closure, not just any callable, because the
     * rule object itself might be callable.
     * 
     */
    public function __construct(array $registry = [])
    {
        foreach ($registry as $name => $spec) {
            $this->set($name, $spec);
        }
    }

    /**
     * 
     * Sets a rule into the registry by name.
     * 
     * @param string $name The rule name; this doubles as a method name
     * when called from a template.
     * 
     * @param string $spec The rule specification, typically a closure that
     * builds and returns a rule object.
     * 
     * @return void
     * 
     */
    public function set($name, $spec)
    {
        $this->registry[$name] = $spec;
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

        if ($this->registry[$name] instanceof \Closure) {
            $func = $this->registry[$name];
            $this->registry[$name] = $func();
        }

        return $this->registry[$name];
    }
}
