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
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Rule for International Standard Book Numbers (ISBN).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Isbn
{
    /**
     *
     * Validates that the value is a ISBN.
     *
     * All values will be sanitized before tested!
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($object, $field)
    {
        $value = $this->normalize($object, $field);
        if (! $value) {
            return false;
        }
        return $this->isbn13($value) || $this->isbn10($value);
    }

    /**
     *
     * Removes all non numeric values to test if it is a valid ISBN.
     *
     * @return mixed
     *
     */
    public function normalize($object, $field)
    {
        $value = preg_replace('/(?:(?!([0-9|X$])).)*/', '', $object->$field);
        if (preg_match('/^[0-9]{10,13}$|^[0-9]{9}X$/', $value)) {
            return $value;
        }
        return false;
    }

    /**
     *
     * Tests if a 13 digit ISBN is correct.
     *
     * @param $value
     *
     * @return bool
     *
     */
    protected function isbn13($value)
    {
        if (strlen($value) != 13) {
            return false;
        }

        $even = $value{0}  + $value{2}  + $value{4} + $value{6}
               + $value{8} + $value{10} + $value{12};

        $odd   = $value{1} + $value{3} + $value{5} + $value{7}
               + $value{9} + $value{11};

        $sum   = $even + ($odd * 3);

        if ($sum % 10) {
            return false;
        }

        return true;
    }

    /**
     *
     * Tests if a 10 digit ISBN is correct.
     *
     * @param $value
     *
     * @return bool
     *
     */
    protected function isbn10($value)
    {
        if (strlen($value) != 10) {
            return false;
        }

        $sum = $value{0}
             + $value{1} * 2
             + $value{2} * 3
             + $value{3} * 4
             + $value{4} * 5
             + $value{5} * 6
             + $value{6} * 7
             + $value{7} * 8
             + $value{8} * 9;

        if ($value{9} == 'X') {
            $sum += 100;
        } else {
            $sum += $value{9} * 10;
        }

        if ($sum % 11) {
            return false;
        }

        return true;
    }
}
