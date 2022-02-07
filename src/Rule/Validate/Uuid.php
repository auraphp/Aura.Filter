<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Validate;

use Aura\Filter\Rule\AbstractUuid;

/**
 *
 * Validates that the value is a canonical human-readable UUID.
 *
 * @package Aura.Filter
 *
 */
class Uuid extends AbstractUuid
{
    /**
     *
     * Validates that the value is a canonical human-readable UUID.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        return $this->isCanonical($subject->$field);
    }
}
