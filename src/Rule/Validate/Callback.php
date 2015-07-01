<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Rule to apply a callable/callback to the data.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Callback
{
    /**
     *
     * Validates the value against a callable/callback.
     *
     * @param callable $callable A PHP callable/callback.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, callable $callable)
    {
        return $callable($subject, $field);
    }
}
