<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Sanitize;

use Aura\Filter\Rule\AbstractBoolean;

/**
 *
 * Sanitize the value to a boolean, or a pseudo-boolean.
 *
 * @package Aura.Filter
 *
 */
class Boolean extends AbstractBoolean
{
    /**
     *
     * Sanitize the value to a boolean, or a pseudo-boolean.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $true Use this value for `true`.
     *
     * @param mixed $false Use this value for `false`.
     *
     * @return bool Always true.
     *
     */
    public function __invoke(object $subject, string $field, $true = true, $false = false): bool
    {
        $value = $subject->$field;

        if ($this->isTrue($value)) {
            $subject->$field = $true;
            return true;
        }

        if ($this->isFalse($value)) {
            $subject->$field = $false;
            return true;
        }

        $subject->$field = $value ? $true : $false;
        return true;
    }
}
