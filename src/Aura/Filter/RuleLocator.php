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
 * A ServiceLocator implementation for loading and retaining helper objects.
 * 
 * @package Aura.Filter
 * 
 */
class RuleLocator
{
    /**
     * 
     * A registry to retain helper objects.
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
     * helper name (doubles as a method name) and the value is the helper
     * object. The value may also be a closure that returns a helper object.
     * Note that is has to be a closure, not just any callable, because the
     * helper object itself might be callable.
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
     * Sets a helper into the registry by name.
     * 
     * @param string $name The helper name; this doubles as a method name
     * when called from a template.
     * 
     * @param string $spec The helper specification, typically a closure that
     * builds and returns a helper object.
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
     * Gets a helper from the registry by name.
     * 
     * @param string $name The helper to retrieve.
     * 
     * @return AbstractRule A helper object.
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
