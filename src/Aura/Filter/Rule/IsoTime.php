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
 * Sanitizes a value to an ISO-8601 time.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class IsoTime extends IsoTimestamp
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_ISO_TIME';

    /**
     * 
     * Validates that the value is an ISO 8601 time string (hh:ii::ss format).
     * 
     * As an alternative, the value may be an array with all of the keys for
     * `H`, `i`, and optionally `s`, in which case the value is
     * converted to an ISO 8601 string before validating it.
     * 
     * Per note from Chris Drozdowski about ISO 8601, allows two
     * midnight times ... 00:00:00 for the beginning of the day, and
     * 24:00:00 for the end of the day.
     * 
     * @return bool True if valid, false if not.
     * 
     * @todo Allow for DateTime objects.
     * 
     */
    protected function validate()
    {
        $value = $this->getValue();

        // look for His keys?
        if (is_array($value)) {
            $value = $this->arrayToTime($value);
        }

        return $this->isTime($value);
    }

    /**
     * 
     * Forces the value to an ISO-8601 formatted time ("hh:ii:ss").
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize()
    {
        $value = $this->getValue();

        // look for His keys?
        if (is_array($value)) {
            $value = $this->arrayToTime($value);
        }

        // normal sanitize
        $format = 'H:i:s';
        if (is_int($value)) {
            $this->setValue(date($format, $value));
        } else {
            $this->setValue(date($format, strtotime($value)));
        }

        // done
        return true;
    }
}

