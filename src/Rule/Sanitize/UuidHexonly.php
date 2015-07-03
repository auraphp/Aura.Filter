<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

use Aura\Filter\Rule\AbstractUuid;

/**
 *
 * Forces the value to a hex-only UUID.
 *
 * @package Aura.Filter
 *
 */
class UuidHexonly extends AbstractUuid
{
    /**
     *
     * Forces the value to a hex-only UUID.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
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
