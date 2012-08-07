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
 * Sanitizes a value to an ISO-8601 timestamp.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class IsoTimestamp extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_ISO_TIMESTAMP';

    /**
     * 
     * Validates that the value is an ISO 8601 timestamp string.
     * 
     * The format is "yyyy-mm-dd hh:ii:ss". As an
     * alternative, the value may be an array with all of the keys for
     * `Y, m, d, H, i`, and optionally `s`, in which case the value is
     * converted to an ISO 8601 string before validating it.
     * 
     * Also checks that the date itself is valid (for example, no Feb 30).
     * 
     * @return bool True if valid, false if not.
     * 
     * @todo Allow for DateTime objects.
     * 
     */
    protected function validate()
    {
        $value = $this->getValue();

        // look for YmdHis keys?
        if (is_array($value)) {
            $value = $this->arrayToTimestamp($value);
        }

        // correct length?
        if (strlen($value) != 19) {
            return false;
        }

        // valid date?
        $date = substr($value, 0, 10);
        if (! $this->isDate($date)) {
            return false;
        }

        // valid separator?
        $sep = substr($value, 10, 1);
        if ($sep != ' ') {
            return false;
        }

        // valid time?
        $time = substr($value, 11, 8);
        if (! $this->isTime($time)) {
            return false;
        }

        // must be ok
        return true;
    }

    /**
     * 
     * checks whether its a date
     * 
     * @param string $value
     * 
     * @return boolean
     */
    protected function isDate($value)
    {
        $expr = '/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/D';
        return preg_match($expr, $value, $matches)
            && checkdate($matches[2], $matches[3], $matches[1]);
    }

    /**
     * 
     * checks whether its time or not
     * 
     * @param string $value
     * 
     * @return boolean
     */
    protected function isTime($value)
    {
        $expr = '/^(([0-1][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9]$/D';
        return preg_match($expr, $value)
            || $value == '24:00:00';
    }

    /**
     * 
     * Forces the value to an ISO-8601 formatted timestamp using a space
     * separator ("yyyy-mm-dd hh:ii:ss") instead of a "T" separator.
     * 
     * The value to be sanitized.  If an integer, it
     * is used as a Unix timestamp; otherwise, converted to a Unix
     * timestamp using [[php::strtotime() | ]].  If an array, and it has *all*
     * the keys for `Y, m, d, h, i, s`, then the array is converted into
     * an ISO 8601 string before sanitizing.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize()
    {
        $value = $this->getValue();

        // look for YmdHis keys
        if (is_array($value)) {
            $value = $this->arrayToTimestamp($value);
        }

        // cast to iso format
        $format = 'Y-m-d H:i:s';
        if (is_int($value)) {
            $this->setValue(date($format, $value));
        } else {
            $this->setValue(date($format, strtotime($value)));
        }

        // done
        return true;
    }

    /**
     * 
     * Converts an array of timestamp parts to a string timestamp.
     * 
     * @param array $array The array of timestamp parts.
     * 
     * @return string
     * 
     */
    protected function arrayToTimestamp($array)
    {
        $value = $this->arrayToDate($array)
               . ' '
               . $this->arrayToTime($array);

        return trim($value);
    }

    /**
     * 
     * Converts an array of date parts to a string date.
     * 
     * @param array $array The array of date parts.
     * 
     * @return string
     * 
     */
    protected function arrayToDate($array)
    {
        $date = array_key_exists('Y', $array) &&
                trim($array['Y']) != '' &&
                array_key_exists('m', $array) &&
                trim($array['m']) != '' &&
                array_key_exists('d', $array) &&
                trim($array['d']) != '';

        if (! $date) {
            return;
        }

        return $array['Y'] . '-'
             . $array['m'] . '-'
             . $array['d'];
    }

    /**
     * 
     * Converts an array of time parts to a string time.
     * 
     * @param array $array The array of time parts.
     * 
     * @return string
     * 
     */
    protected function arrayToTime($array)
    {
        $time = array_key_exists('H', $array) &&
                trim($array['H']) != '' &&
                array_key_exists('i', $array) &&
                trim($array['i']) != '';

        if (! $time) {
            return;
        }

        $s = array_key_exists('s', $array) && trim($array['s']) != ''
           ? $array['s']
           : '00';

        return $array['H'] . ':'
             . $array['i'] . ':'
             . $s;
    }
}

