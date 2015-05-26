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
namespace Aura\Filter\Rule\Sanitize;

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
     * Sanitizes a value using a callable/callback.
     *
     * @param callable $callable A callable/callback.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field, callable $callable)
    {
        return $callable($subject, $field);
    }
}
