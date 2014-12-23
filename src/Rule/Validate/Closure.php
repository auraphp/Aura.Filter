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

use \Closure as PhpClosure;

/**
 *
 * Rule to apply a closure to the data.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Closure extends AbstractRule
{
    /**
     *
     * Validates the value against a closure.
     *
     * @param \Closure $closure A PHP closure.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate(PhpClosure $closure)
    {
        $closure = $closure->bindTo($this, get_class($this));

        return $closure();
    }

    /**
     *
     * Sanitizes a value using a closure.
     *
     * @param \Closure $closure A PHP closure.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize(PhpClosure $closure)
    {
        $closure = $closure->bindTo($this, get_class($this));

        return $closure();
    }
}
