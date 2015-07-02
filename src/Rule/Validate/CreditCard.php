<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates the value as a credit card number.
 *
 * @package Aura.Filter
 *
 */
class CreditCard
{
    /**
     *
     * Validates the value as a credit card number.
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
        // get the value; remove spaces, dashes, and dots
        $value = str_replace(array(' ', '-', '.'), '', (string) $subject->$field);

        // is it composed only of digits?
        if (! ctype_digit($value)) {
            return false;
        }

        // luhn mod-10 algorithm: https://gist.github.com/1287893
        $sumTable = array(
            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            array(0, 2, 4, 6, 8, 1, 3, 5, 7, 9),
        );

        $sum = 0;
        $flip = 0;

        for ($i = strlen($value) - 1; $i >= 0; $i--) {
            $sum += $sumTable[$flip++ & 0x1][$value[$i]];
        }

        return $sum % 10 === 0;
    }
}
