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

use Aura\Filter\Rule\AbstractUuid;

/**
 *
 * Validates that the value is a hex-only UUID.
 *
 * @package Aura.Filter
 *
 */
class UuidHexonly extends AbstractUuid
{
    /**
     *
     * Validates that the value is a hex-only UUID.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke(object $subject, string $field): bool
    {
        return $this->isHexOnly($subject->$field);
    }
}
