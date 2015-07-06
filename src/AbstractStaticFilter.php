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
    protected static $instance;

    /**
     *
     * Prevents construction of the static proxy as an instance.
     *
     * @codeCoverageIgnore
     *
     */
    private function __construct()
    {
    }

    /**
     *
     * Sets the proxied filter instance.
     *
     * @param ValueFilter $instance The proxied filter instance.
     *
     * @return null
     *
     */
    public static function setInstance(ValueFilter $instance)
    {
        if (static::$instance) {
            $class = get_called_class();
            throw new Exception("{$class}::\$instance is already set.");
        }
        static::$instance = $instance;
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
        if (! static::$instance) {
            $class = get_called_class();
            throw new Exception("{$class}::\$instance not set.");
        }

        $args = func_get_args();
        return call_user_func_array(
            array(static::$instance, 'validate'),
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
        if (! static::$instance) {
            $class = get_called_class();
            throw new Exception("{$class}::\$instance not set.");
        }

        $args = func_get_args();
        $args[0] = &$value;
        return call_user_func_array(
            array(static::$instance, 'sanitize'),
            $args
        );
    }
}
