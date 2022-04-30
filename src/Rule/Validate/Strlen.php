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
 * Validates that the length of the value is within a given range.
 *
 * @package Aura.Filter
 *
 */
class Strlen extends AbstractStrlen
{
    /**
     *
     * Validates that the length of the value is within a given range.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param int $len String length equal to length.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke(object $subject, string $field, int $len): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return $this->strlen((string) $value) == $len;
    }
}
