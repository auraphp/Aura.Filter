<?php
/**
 *
 * This file is part of the Aura Project for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Locator;

use Aura\Filter\Exception;

/**
 *
 * A ServiceLocator implementation for loading and retaining rule objects.
 *
 * @package Aura.Filter
 *
 */
class Locator
{
    /**
     *
     * Factories to create rule objects.
     *
     * @var array
     *
     */
    protected $factories = array();

    /**
     *
     * Rule object instances.
     *
     * @var array
     *
     */
    protected $instances = array();

    /**
     *
     * Constructor.
     *
     * @param array $factories An array of key-value pairs where the key is the
     * rule name and the value is a callable that returns a rule object.
     *
     */
    public function __construct(array $factories = array())
    {
        $this->initFactories($factories);
    }

    /**
     *
     * Initialize the $factories property for the first time.
     *
     * @param array $factories An array of key-value pairs where the key is the
     * rule name and the value is a callable that returns a rule object.
     *
     * @return null
     *
     */
    protected function initFactories(array $factories)
    {
        foreach ($factories as $name => $spec) {
            $this->set($name, $spec);
        }
    }

    /**
     *
     * Sets a rule factory by name.
     *
     * @param string $name The rule name.
     *
     * @param callable $spec A callable that returns a rule.
     *
     * @return void
     *
     */
    public function set($name, $spec)
    {
        $this->factories[$name] = $spec;
        unset($this->instances[$name]);
    }

    /**
     *
     * Gets a rule by name, whether an existing instance or from a factory.
     *
     * @param string $name The rule to retrieve.
     *
     * @return callable A callable rule.
     *
     * @throws Exception\RuleNotMapped
     *
     */
    public function get($name)
    {
        $mapped = isset($this->factories[$name])
               || isset($this->instances[$name]);

        if (! $mapped) {
            throw new Exception\RuleNotMapped($name);
        }

        if (! isset($this->instances[$name])) {
            $this->instances[$name] = call_user_func($this->factories[$name]);
        }

        return $this->instances[$name];
    }
}
