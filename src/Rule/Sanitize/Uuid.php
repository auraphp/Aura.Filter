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
 * Forces the value to a canonical UUID.
 *
 * @package Aura.Filter
 *
 */
class Uuid extends AbstractUuid
{
    /**
     *
     * Forces the value to a canonical UUID.
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
        $value = $subject->$field;
        if ($this->isCanonical($value)) {
            // already a canonical value
            return true;
        }

        // force to hex only
        $value = preg_replace('/[^a-f0-9]/i', '', $subject->$field);
        if (! $this->isHexOnly($value)) {
            // not hex-only, cannot sanitize
            return false;
        }

        // add the dashes
        $subject->$field = substr($value, 0, 8) . '-'
                         . substr($value, 8, 4) . '-'
                         . substr($value, 12, 4) . '-'
                         . substr($value, 16, 4) . '-'
                         . substr($value, 20);

        // done!
        return true;
     }
}
