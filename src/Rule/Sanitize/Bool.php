<?php
/**
 *
 * This file is part of the Aura project for PHP.
 *
 * @package Aura.Filter
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
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Bool extends AbstractBool
{
    /**
     *
     * Forces the value to a boolean.
     *
     * Note that this recognizes $this->true and $this->false values.
     *
     * @return bool Always true.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;

        if ($this->isTrue($value)) {
            $subject->$field = true;
            return true;
        }

        if ($this->isFalse($value)) {
            $subject->$field = false;
            return true;
        }

        $subject->$field = (bool) $value;
        return true;
    }
}
