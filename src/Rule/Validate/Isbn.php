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
 * Validates that the value represents an ISBN.
 *
 * @package Aura.Filter
 *
 */
class Isbn
{
    /**
     *
     * Validates that the value represents an ISBN.
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
        $value = $this->normalize($subject, $field);
        if (! $value) {
            return false;
        }
        return $this->isbn13($value) || $this->isbn10($value);
    }

    /**
     *
     * Removes all non-ISBN characters to test if it is a valid ISBN.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return string|false The normalized string, or false on failure.
     *
     */
    public function normalize($subject, $field)
    {
        $value = preg_replace('/(?:(?!([0-9|X$])).)*/', '', $subject->$field);
        if (preg_match('/^[0-9]{10,13}$|^[0-9]{9}X$/', $value)) {
            return $value;
        }
        return false;
    }

    /**
     *
     * Tests if a 13 digit ISBN is correct.
     *
     * @param $value The value to test.
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
     * @param $value The value to test.
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
