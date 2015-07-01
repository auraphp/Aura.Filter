<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

use Aura\Filter\Rule\AbstractBool;

/**
 *
 * Rule for booleans.
 *
 * @package Aura.Filter
 *
 */
class Bool extends AbstractBool
{
    /**
     *
     * Forces the value to a boolean, or an alternative boolean value.
     *
     * @return bool Always true.
     *
     */
    public function __invoke($subject, $field, $true = true, $false = false)
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
