<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates a value is an IP address.
 *
 * @package Aura.Filter
 *
 */
class Ip
{
    /**
     *
     * Validates that the value is an IP address.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $flags `FILTER_VALIDATE_IP` flags to pass to filter_var();
     * cf. <http://php.net/manual/en/filter.filters.flags.php>.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, $flags = null)
    {
        if ($flags === null) {
            $flags = FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6;
        }

        $value = $subject->$field;
        return filter_var($value, FILTER_VALIDATE_IP, $flags) !== false;
    }
}
