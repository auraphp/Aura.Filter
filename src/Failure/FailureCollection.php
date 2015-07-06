<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Failure;

use ArrayObject;

/**
 *
 * A collection of Failure objects.
 *
 * @package Aura.Filter
 *
 */
class FailureCollection extends ArrayObject
{
    /**
     *
     * Constructor.
     *
     * @return self
     *
     */
    public function __construct()
    {
        parent::__construct(array());
    }

    /**
     *
     * Is the failure collection empty?
     *
     * @return bool
     *
     */
    public function isEmpty()
    {
        return count($this) === 0;
    }

    /**
     *
     * Set a failure on a field, removing all previous failures.
     *
     * @param string $field The field that failed.
     *
     * @param string $message The failure message.
     *
     * @param array $args The arguments passed to the rule specification.
     *
     * @return Failure
     *
     */
    public function set($field, $message, array $args = array())
    {
        $failure = $this->newFailure($field, $message, $args);
        $this[$field] = array($failure);
        return $failure;
    }

    /**
     *
     * Adds an additional failure on a field.
     *
     * @param string $field The field that failed.
     *
     * @param string $message The failure message.
     *
     * @param array $args The arguments passed to the rule specification.
     *
     * @return Failure
     *
     */
    public function add($field, $message, array $args = array())
    {
        $failure = $this->newFailure($field, $message, $args);
        $this[$field][] = $failure;
        return $failure;
    }

    /**
     *
     * Factory method to return a new Failure object.
     *
     * @param string $field The field that failed.
     *
     * @param string $message The failure message.
     *
     * @param array $args The arguments passed to the rule specification.
     *
     * @return Failure
     *
     */
    protected function newFailure($field, $message, array $args = array())
    {
        return new Failure($field, $message, $args);
    }

    /**
     *
     * Returns all failures for a field.
     *
     * @param string $field The field name.
     *
     * @return array
     *
     */
    public function forField($field)
    {
        if (! isset($this[$field])) {
            return array();
        }

        return $this[$field];
    }

    /**
     *
     * Returns all failure messages for all fields.
     *
     * @return array
     *
     */
    public function getMessages()
    {
        $messages = array();
        foreach ($this as $field => $failures) {
            $messages[$field] = $this->getMessagesForField($field);
        }
        return $messages;
    }

    /**
     *
     * Returns all failure messages for one field.
     *
     * @param string $field The field name.
     *
     * @return array
     *
     */
    public function getMessagesForField($field)
    {
        if (! isset($this[$field])) {
            return array();
        }

        $messages = array();
        foreach ($this[$field] as $failure) {
            $messages[] = $failure->getMessage();
        }
        return $messages;
    }

    /**
     *
     * Returns a single string of all failure messages for all fields.
     *
     * @param string $prefix Prefix each line with this string.
     *
     * @return string
     *
     */
    public function getMessagesAsString($prefix = '')
    {
        $string = '';
        foreach ($this as $field => $failures) {
            foreach ($failures as $failure) {
                $message = $failure->getMessage();
                $string .= "{$prefix}{$field}: {$message}" . PHP_EOL;
            }
        }
        return $string;
    }

    /**
     *
     * Returns a single string of all failure messages for one field.
     *
     * @param string $field The field name.
     *
     * @param string $prefix Prefix each line with this string.
     *
     * @return string
     *
     */
    public function getMessagesForFieldAsString($field, $prefix = '')
    {
        $string = '';
        foreach ($this->forField($field) as $failure) {
            $message = $failure->getMessage();
            $string .= "{$prefix}{$message}" . PHP_EOL;
        }
        return $string;
    }
}
