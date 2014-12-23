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
namespace Aura\Filter\Rule;

/**
 *
 * Rule to call a method on the value object; the method should return a
 * boolean to indicate if the filter passed or failed.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Method extends AbstractRule
{
    /**
     *
     * Calls a method on the value object to validate itself; the method
     * should return a boolean to indicate if the filter passed or failed.
     *
     * @param string $method The method to call on the value object.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($method)
    {
        $this->setParams(get_defined_vars());

        return (bool) $this->call(func_get_args());
    }

    /**
     *
     * Calls a method on the value object to sanitize itself; the method
     * should return a boolean to indicate if the filter passed or failed.
     *
     * @param string $method The method to call on the value object.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($method)
    {
        $this->setParams(get_defined_vars());

        return (bool) $this->call(func_get_args());
    }

    /**
     *
     * Calls a method on the value object.
     *
     * @param array $args The arguments to pass to the method call (the first
     *                    is the method name itself).
     *
     * @return mixed
     *
     */
    protected function call(array $args)
    {
        $object = $this->getValue();
        $method = array_shift($args);

        return is_object($object)
            && is_callable([$object, $method])
            && call_user_func_array([$object, $method], $args);
    }
}