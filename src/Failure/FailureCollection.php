<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Failure;

use ArrayObject;
use Aura\Filter_Interface\FailureInterface;
use Aura\Filter_Interface\FailureCollectionInterface;

/**
 *
 * A collection of Failure objects.
 *
 * @package Aura.Filter
 *
 */
class FailureCollection extends ArrayObject implements FailureCollectionInterface
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
     *
     */
    public function isEmpty(): bool
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
     *
     */
    public function set(string $field, string $message, array $args = array()): FailureInterface
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
     */
    public function add(string $field, string $message, array $args = array()): FailureInterface
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
     *
     */
    protected function newFailure(string $field, string $message, array $args = array()): FailureInterface
    {
        return new Failure($field, $message, $args);
    }

    /**
     *
     * Returns all failures for a field.
     *
     * @param string $field The field name.
     *
     *
     * @return array<int, Failure>
     */
    public function forField(string $field): array
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
     *
     * @return array<string, array<int, string>>
     */
    public function getMessages(): array
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
     * @return array<int, string>
     *
     */
    public function getMessagesForField(string $field): array
    {
        if (! isset($this[$field])) {
            return array();
        }

        $messages = array();

        if ($this[$field] instanceof FailureCollection) {
            $messages = $this[$field]->getMessages();
        } else {
            foreach ($this[$field] as $failure) {
                $messages[] = $failure->getMessage();
            }
        }

        return $messages;
    }

    /**
     *
     * Returns a single string of all failure messages for all fields.
     *
     * @param string $prefix Prefix each line with this string.
     *
     *
     */
    public function getMessagesAsString(string $prefix = ''): string
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
     *
     */
    public function getMessagesForFieldAsString(string $field, string $prefix = ''): string
    {
        $string = '';
        foreach ($this->forField($field) as $failure) {
            $message = $failure->getMessage();
            $string .= "{$prefix}{$message}" . PHP_EOL;
        }
        return $string;
    }

    public function addSubfieldFailures($field, $spec)
    {
        $failure_collection = new FailureCollection();

        foreach ($spec->getMessage() as $f => $messages) {
            foreach ($messages as $message) {
                $failure_collection->add($f, $message, $spec->getArgs());
            }
        }

        $this[$field] = $failure_collection;
    }
}
