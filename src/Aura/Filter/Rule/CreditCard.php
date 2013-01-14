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

use Aura\Filter\AbstractRule;

/**
 * 
 * Validates the value as a credit card number.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class CreditCard extends AbstractRule
{
    /**
     *
     * Error message.
     * 
     * @var string
     * å
     */
    protected $message = 'FILTER_CREDIT_CARD';

    /**
     * 
     * Validates the value as a credit card number.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate()
    {
        // get the value; remove spaces, dashes, and dots
        $value = str_replace([' ', '-', '.'], '', (string) $this->getValue());

        // is it composed only of digits?
        if (! ctype_digit($value)) {
            return false;
        }

        // luhn mod-10 algorithm: https://gist.github.com/1287893
        $sumTable = [
            [0,1,2,3,4,5,6,7,8,9],
            [0,2,4,6,8,1,3,5,7,9],
        ];

        $sum = 0;
        $flip = 0;

        for ($i = strlen($value) - 1; $i >= 0; $i--) {
            $sum += $sumTable[$flip++ & 0x1][$value[$i]];
        }
        return $sum % 10 === 0;
    }

    /**
     * 
     * Can't fix credit card numbers.
     * 
     * @return false
     * 
     */
    public function sanitize()
    {
        return false;
    }
}
