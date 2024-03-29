<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates the value against a callable/callback.
 *
 * @package Aura.Filter
 *
 */
class Callback
{
    /**
     *
     * Validates the value against a callable/callback.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param callable $callable A PHP callable/callback.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke(object $subject, string $field, callable $callable): bool
    {
        return $callable($subject, $field);
    }
}
