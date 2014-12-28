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
namespace Aura\Filter\Rule\Validate;

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
class Closure
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
    public function __invoke($object, $field, PhpClosure $closure)
    {
        $closure = $closure->bindTo($this, get_class($this));

        return $closure();
    }
}
