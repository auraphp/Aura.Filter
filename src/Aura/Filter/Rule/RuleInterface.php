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

use StdClass;

/**
 * 
 * Rule Interface
 * 
 * @package Aura.Filter
 * 
 */
interface RuleInterface
{
    /**
     * 
     * Prepare the rule for reuse.
     * 
     * @param StdClass $data The full set of data to be filtered.
     * 
     * @param string $field The field to be filtered within the data.
     * 
     * @return void
     * 
     */
    public function prep(StdClass $data, $field);

    /**
     * 
     * Get the error message; note that this returns the message whether or
     * not there was an error when validating or sanitizing.
     * 
     * @return string
     * 
     */
    public function getMessage();

    /**
     * 
     * Get the value of the field being filtered, or null if the field is
     * not set in the data.
     * 
     * @return mixed
     * 
     */
    public function getValue();

    /**
     * 
     * Set value of field, creating it in the data if needed.
     * 
     * @param string $value The new value of the field.
     * 
     * @return void
     * 
     */
    public function setValue($value);

    /**
     * 
     * Is the value valid?
     * 
     * @return bool True if valid, false if not valid.
     * 
     */
    public function is();

    /**
     * 
     * Is the value *not* valid?
     * 
     * @return bool True if not valid, false if valid.
     * 
     */
    public function isNot();

    /**
     * 
     * Is the value blank, or otherwise valid?
     * 
     * @return bool True if blank or valid, false if not.
     * 
     */
    public function isBlankOr();

    /**
     * 
     * Sanitize the value, transforming it as needed.
     * 
     * @return bool True if the value was sanitized, false if not.
     * 
     */
    public function fix();

    /**
     * 
     * If the value is blank, set to null; sanitize if not blank, transforming
     * it as needed.
     * 
     * @return bool True if the value was set to null or sanitized, false if
     * not.
     * 
     */
    public function fixBlankOr();
}

