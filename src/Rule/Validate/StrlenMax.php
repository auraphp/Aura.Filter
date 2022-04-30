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

use Aura\Filter\Rule\AbstractStrlen;

/**
 *
 * Validates that a value is no longer than a certain length.
 *
 * @package Aura.Filter
 *
 */
class StrlenMax extends AbstractStrlen
{
    /**
     *
     * Validates that a value is no longer than a certain length.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param int|float $max The value must have no more than this many
     * characters.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke(object $subject, string $field, $max): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return $this->strlen($value) <= $max;
    }
}
