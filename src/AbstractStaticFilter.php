<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter;

/**
 *
 * A static proxy to a ValueFilter instance.
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractStaticFilter
{
    /**
     *
     * The proxied filter instance.
     *
     * @var ValueFilter
     *
     */
    protected static $singleton;

    /**
     *
     * Prevents construction of the static proxy as an instance.
     *
     */
    private function __construct()
    {
    }

    /**
     *
     * Sets the proxied filter instance.
     *
     * @param ValueFilter $singleton The proxied filter instance.
     *
     * @return null
     *
     */
    public static function setSingleton(ValueFilter $singleton)
    {
        if (static::$singleton) {
            $class = get_called_class();
            throw new Exception("{$class}::\$singleton is already set.");
        }
        static::$singleton = $singleton;
    }

    /**
     *
     * Validates a value using a rule.
     *
     * @param mixed $value The value to validate.
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments to pass to the rule.
     *
     * @return bool True on success, false on failure.
     *
     */
    public static function validate($value, $rule)
    {
        if (! static::$singleton) {
            $class = get_called_class();
            throw new Exception("{$class}::\$singleton not set.");
        }

        $args = func_get_args();
        return call_user_func_array(
            array(static::$singleton, 'validate'),
            $args
        );
    }

    /**
     *
     * Sanitizes a value in place using a rule.
     *
     * @param mixed $value The value to sanitize.
     *
     * @param string $rule The rule name.
     *
     * @param ...$args Arguments to pass to the rule.
     *
     * @return bool True on success, false on failure.
     *
     */
    public static function sanitize(&$value, $rule)
    {
        if (! static::$singleton) {
            throw new Exception('StaticValueFilter::$singleton not set.');
        }

        $args = func_get_args();
        $args[0] = &$value;
        return call_user_func_array(
            array(static::$singleton, 'sanitize'),
            $args
        );
    }
}
