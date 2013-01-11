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

use DateTime as PhpDateTime;

/**
 * 
 * Validate and Sanitize date time
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class DateTime extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string 
     * 
     */
    protected $message = 'FILTER_DATETIME';

    /**
     * 
     * validate datetime of default format Y-m-d H:i:s
     * 
     * @param string $format
     * 
     * @return boolean
     * 
     */
    public function validate($format = 'Y-m-d H:i:s')
    {
        $value = $this->getValue();

        if ($value instanceof PhpDateTime) {
            return true;
        }

        if (! is_scalar($value)) {
            return false;
        }

        if (trim($value) === '') {
            return false;
        }

        return (bool) date_create($value);
    }

    /**
     * sanitize datetime to default format Y-m-d H:i:s
     * 
     * @param string $format
     * 
     * @return boolean
     */
    public function sanitize($format = 'Y-m-d H:i:s')
    {
        $value = $this->getValue();

        if ($value instanceof PhpDateTime) {
            $datetime = $value;
        } elseif (! is_scalar($value)) {
            return false;
        } else {
            $datetime = date_create($value);
        }

        if (! $datetime) {
            return false;
        }

        $this->setValue($datetime->format($format));
        return true;
    }
}
