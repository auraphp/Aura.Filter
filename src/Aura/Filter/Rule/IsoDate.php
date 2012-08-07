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
 * Sanitizes a value to an ISO-8601 date.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class IsoDate extends IsoTimestamp
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_ISO_DATE';

    /**
     * 
     * Validates that the value is an ISO 8601 date string.
     * 
     * The format is "yyyy-mm-dd".  Also checks to see that the date
     * itself is valid (for example, no Feb 30).
     * 
     * @return bool True if valid, false if not.
     * 
     * @todo Allow for DateTime objects.
     * 
     */
    protected function validate()
    {
        $value = $this->getValue();

        // look for Ymd keys?
        if (is_array($value)) {
            $value = $this->arrayToDate($value);
        }

        return $this->isDate($value);
    }

    /**
     * 
     * Forces the value to an ISO-8601 formatted date ("yyyy-mm-dd").
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize()
    {
        $value = $this->getValue();

        // look for Ymd keys?
        if (is_array($value)) {
            $value = $this->arrayToDate($value);
        }

        // normal sanitize
        $format = 'Y-m-d';
        if (is_int($value)) {
            $this->setValue(date($format, $value));
        } else {
            $this->setValue(date($format, strtotime($value)));
        }

        // done
        return true;
    }
}

