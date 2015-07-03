<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Failure;

/**
 *
 * Creates Failure objects.
 *
 * @package Aura.Filter
 *
 */
class FailureFactory
{
    /**
     *
     * Returns a new Failure object.
     *
     * @param string $field The field that failed.
     *
     * @param string $mode The failure mode.
     *
     * @param string $message The failure message.
     *
     * @param array $args The arguments passed to the rule specification.
     *
     * @return Failure
     *
     */
    public function newInstance($field, $mode, $message, array $args = array())
    {
        return new Failure($field, $mode, $message, $args);
    }
}
