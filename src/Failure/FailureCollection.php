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

use Aura\Filter_Interface\FailureCollectionInterface;
use Aura\Filter_Interface\FailureInterface;

/**
 *
 * A collection of Failure objects, implementing the read+write
 * FailureCollectionInterface (which extends the read-only FailuresInterface).
 *
 * @package Aura.Filter
 *
 */
class FailureCollection implements FailureCollectionInterface, \JsonSerializable
{
    /**
     * Failures keyed by field name → Failure[].
     *
     * @var array<string, FailureInterface[]>
     */
    private array $items = [];

    // -------------------------------------------------------------------------
    // FailuresInterface — read side
    // -------------------------------------------------------------------------

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * @return FailureInterface[]
     */
    public function forField(string $field): array
    {
        return $this->items[$field] ?? [];
    }

    /**
     * Dot-notation path lookup: "address.city", "items.0.name".
     * Since nested failures are stored with dot-notation keys in the flat
     * collection, this is equivalent to forField().
     *
     * @return FailureInterface[]
     */
    public function forPath(string $path): array
    {
        return $this->forField($path);
    }

    /**
     * Flat map: field/path → string[].
     *
     * @return array<string, string[]>
     */
    public function getMessages(): array
    {
        $messages = [];
        foreach ($this->items as $field => $failures) {
            $messages[$field] = array_map(
                static fn(FailureInterface $f) => $f->getMessage(),
                $failures
            );
        }
        return $messages;
    }

    /**
     * Nested map that mirrors the input data structure.
     * Splits each dot-notation key and builds nested arrays.
     *
     * @return array
     */
    public function getNestedMessages(): array
    {
        $result = [];
        foreach ($this->items as $field => $failures) {
            $messages = array_map(
                static fn(FailureInterface $f) => $f->getMessage(),
                $failures
            );
            $parts = explode('.', $field);
            $node  = &$result;
            foreach ($parts as $i => $part) {
                if ($i === count($parts) - 1) {
                    $node[$part] = $messages;
                } else {
                    if (! isset($node[$part]) || ! is_array($node[$part])) {
                        $node[$part] = [];
                    }
                    $node = &$node[$part];
                }
            }
            unset($node);
        }
        return $result;
    }

    // -------------------------------------------------------------------------
    // FailureCollectionInterface — write side
    // -------------------------------------------------------------------------

    /**
     * Appends a failure for a field.
     *
     * @param string  $field   The field that failed.
     * @param string  $message The failure message.
     * @param mixed[] $args    Arguments passed to the rule specification.
     */
    public function add(string $field, string $message, array $args = []): FailureInterface
    {
        $failure               = new Failure($field, $message, $args);
        $this->items[$field][] = $failure;
        return $failure;
    }

    /**
     * Sets a single failure for a field, replacing any previous failures for that field.
     *
     * @param string  $field   The field that failed.
     * @param string  $message The failure message.
     * @param mixed[] $args    Arguments passed to the rule specification.
     */
    public function set(string $field, string $message, array $args = []): FailureInterface
    {
        $failure             = new Failure($field, $message, $args);
        $this->items[$field] = [$failure];
        return $failure;
    }

    // -------------------------------------------------------------------------
    // Concrete helpers — not on any interface
    // -------------------------------------------------------------------------

    /**
     * Returns all failure message strings for one field.
     *
     * @return string[]
     */
    public function getMessagesForField(string $field): array
    {
        return array_map(
            static fn(FailureInterface $f) => $f->getMessage(),
            $this->forField($field)
        );
    }

    /**
     * Returns a single string of all failure messages for all fields.
     */
    public function getMessagesAsString(string $prefix = ''): string
    {
        $string = '';
        foreach ($this->items as $field => $failures) {
            foreach ($failures as $failure) {
                $string .= "{$prefix}{$field}: {$failure->getMessage()}" . PHP_EOL;
            }
        }
        return $string;
    }

    /**
     * Returns a single string of all failure messages for one field.
     */
    public function getMessagesForFieldAsString(string $field, string $prefix = ''): string
    {
        $string = '';
        foreach ($this->forField($field) as $failure) {
            $string .= "{$prefix}{$failure->getMessage()}" . PHP_EOL;
        }
        return $string;
    }

    /**
     * Returns a JSON-serializable representation: field name → FailureInterface[].
     *
     * @return array<string, FailureInterface[]>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
