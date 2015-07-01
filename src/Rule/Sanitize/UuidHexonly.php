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
namespace Aura\Filter\Rule\Sanitize;

use Aura\Filter\Rule\AbstractUuid;

/**
 *
 * Rule for hex-only Universally Unique Identifier (UUID).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class UuidHexonly extends AbstractUuid
{
    /**
     *
     * Forces the value to a hex-only UUID.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = preg_replace('/[^a-f0-9]/i', '', $subject->$field);
        if ($this->isHexOnly($value)) {
            $subject->$field = $value;
            return true;
        }
        return false;
    }
}
