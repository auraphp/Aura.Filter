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
 * Rule for International Standard Book Numbers (ISBN).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Isbn extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'           => 'FILTER_RULE_FAILURE_IS_ISBN',
        'failure_is_not'       => 'FILTER_RULE_FAILURE_IS_NOT_ISBN',
        'failure_is_blank_or'  => 'FILTER_RULE_FAILURE_IS_BLANK_OR_ISBN',
        'failure_fix'          => 'FILTER_RULE_FAILURE_FIX_ISBN',
        'failure_fix_blank_or' => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_ISBN',
    ];

    /**
     *
     * Validates that the value is a ISBN.
     *
     * All values will be sanitized before tested!
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate()
    {
        if ($this->sanitize() == true) {
            $value = $this->getValue();

            if (strlen($value) == 13) {
                return $this->thirteen($this->getValue());
            } elseif (strlen($value) == 10) {
                return $this->ten($this->getValue());
            }
        }

        return false;
    }

    /**
     *
     * Removes all non numeric values to test if it is a valid ISBN.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize()
    {
        $value = $this->getValue();
        $value = preg_replace('/(?:(?!([0-9|X$])).)*/', '', $value);

        if (preg_match('/^[0-9]{10,13}$|^[0-9]{9}X$/', $value) == 1) {
            $this->setValue($value);
            return true;
        }

        return false;
    }

    /**
     * 
     * Tests if a 13 digit ISBN is correct.
     *
     * @param $isbn
     * 
     * @return bool
     * 
     */
    private function thirteen($isbn)
    {
        $three = $isbn{0} + $isbn{2} + $isbn{4} + $isbn{6} + $isbn{8} + $isbn{10} + $isbn{12};
        $one   = ($isbn{1} + $isbn{3} + $isbn{5} + $isbn{7} + $isbn{9} + $isbn{11}) * 3;

        if (($three + $one) % 10 == 0) {
            return true;
        }

        return false;
    }

    /**
     * 
     * Tests if a 10 digit ISBN is correct.
     *
     * @param $isbn
     * 
     * @return bool
     * 
     */
    private function ten($isbn)
    {
        $sum = $isbn{0} + $isbn{1} * 2 + $isbn{2} * 3 + $isbn{3} * 4 + $isbn{4} * 5 + $isbn{5} * 6 + $isbn{6} * 7
            + $isbn{7} * 8 + $isbn{8} * 9;

        if ($isbn{9} == 'X') {
            $sum += 100;
        } else {
            $sum += $isbn{9} * 10;
        }

        if ($sum % 11 == 0) {
            return true;
        }

        return false;
    }
}
