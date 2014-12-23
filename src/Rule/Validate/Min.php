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
namespace Aura\Filter\Rule;

/**
 *
 * Validates that a value is greater than or equal to a minimum.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Min
{
    /**
     *
     * Validates that the value is greater than or equal to a minimum.
     *
     * @param mixed $min The minimum valid value.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($min)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }

        return $value >= $min;
    }

    /**
     *
     * Check whether the value is less than min, if so set to min
     *
     * @param int $min
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($min)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if ($value < $min) {
            $this->setValue($min);
        }

        return true;
    }
}
