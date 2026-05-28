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
 * A collection of Failure objects.
 *
 * Failures are stored in a flat map keyed by field name or dot-notation path.
 * Nested failures from sub-filters are stored under their full path
 * (e.g. "address.city", "items.0.name") so that failures from different
 * sub-filters that share a child field name (e.g. address.city vs
 * shipping.city) remain distinguishable.
 *
 * Reading failures
 * ----------------
 * - forField('name') / forPath('address.city') — FailureInterface[] for one key
 * - getMessages()        — flat map: path → string[]
 * - getNestedMessages()  — nested map mirroring the input data shape
 * - getMessagesAsString() — all failures as a single human-readable string
 *
 * Writing failures
 * ----------------
 * - add() — appends a failure (multiple failures per field are supported)
 * - set() — replaces all previous failures for a field with one new failure
 *
 * @package Aura.Filter
 */
class FailureCollection implements FailureCollectionInterface, \JsonSerializable
{
    /**
     * Failures keyed by field name or dot-notation path → Failure[].
     *
     * Sub-filter failures are stored under their full dot-notation path
     * (e.g. "address.city") so that keys from different sub-filters never
     * collide in this flat map.
     *
     * @var array<string, FailureInterface[]>
     */
    private array $items = [];

    // -------------------------------------------------------------------------
    // FailuresInterface — read side
    // -------------------------------------------------------------------------

    /**
     * Returns true when no failures have been recorded.
     */
    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * Returns all Failure objects recorded for a field or dot-notation path.
     *
     * Returns an empty array when no failures exist for the given key.
     *
     * @return FailureInterface[]
     */
    public function forField(string $field): array
    {
        return $this->items[$field] ?? [];
    }

    /**
     * Dot-notation path lookup: "address.city", "items.0.name".
     *
     * Sub-filter failures are stored under their full dot-notation path, so
     * this is the preferred method when working with nested subjects.
     * Internally equivalent to forField().
     *
     * @return FailureInterface[]
     */
    public function forPath(string $path): array
    {
        return $this->forField($path);
    }

    /**
     * Returns all failures as a flat map of path → message strings.
     *
     * Keys are field names or dot-notation paths (e.g. "address.city").
     * Multiple failures for the same field appear as multiple strings in the
     * array value.
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
     * Returns failures as a nested map that mirrors the input data structure.
     *
     * Each dot-notation key is split and placed into a nested array, making it
     * easy to pair error messages with the field that produced them:
     *
     *   // failures: "address.city", "address.zip", "name"
     *   [
     *     'address' => [
     *       'city' => ['City is required'],
     *       'zip'  => ['Zip must be numeric'],
     *     ],
     *     'name' => ['Name is required'],
     *   ]
     *
     * When a path has failures at both a parent level and a nested child level
     * (e.g. "address" and "address.city"), the parent node's own messages are
     * stored under the reserved key "_messages" so that both survive:
     *
     *   [
     *     'address' => [
     *       '_messages' => ['Address block is invalid'],
     *       'city'      => ['City is required'],
     *     ],
     *   ]
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
            $last  = count($parts) - 1;
            foreach ($parts as $i => $part) {
                if ($i === $last) {
                    // Last segment: store this field's own messages.
                    // If the node already has child entries (because a deeper path
                    // was processed earlier), keep the children and add own messages
                    // under the reserved '_messages' key instead of overwriting.
                    if (isset($node[$part]) && is_array($node[$part])) {
                        $node[$part]['_messages'] = $messages;
                    } else {
                        $node[$part] = $messages;
                    }
                } else {
                    if (! isset($node[$part])) {
                        $node[$part] = [];
                    } elseif (array_is_list($node[$part])) {
                        // The node was previously stored as a flat messages list
                        // (i.e. this parent path also had its own failures).
                        // Promote it to a node that holds both own messages and children.
                        $node[$part] = ['_messages' => $node[$part]];
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
     * Appends a failure for a field or dot-notation path.
     *
     * Multiple calls with the same field accumulate failures; they do not
     * replace each other. Use set() when only one failure per field is needed.
     *
     * @param string  $field   Field name or dot-notation path (e.g. "address.city").
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
     * Records a single failure for a field, replacing any previous failures for that field.
     *
     * Useful when only the last (or most authoritative) failure for a field
     * matters, e.g. when a field-level message overrides individual rule messages.
     *
     * @param string  $field   Field name or dot-notation path (e.g. "address.city").
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
     * Returns all failure message strings for one field or dot-notation path.
     *
     * Returns an empty array when no failures exist for the given key.
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
     * Returns all failures across all fields as a single human-readable string.
     *
     * Each line has the format: "{prefix}{field}: {message}".
     * Useful for exception messages and log output.
     *
     * @param string $prefix Optional string prepended to every line (e.g. "  ").
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
     * Returns all failure messages for one field as a single human-readable string.
     *
     * Each line has the format: "{prefix}{message}".
     *
     * @param string $prefix Optional string prepended to every line.
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
     * Returns a JSON-serializable representation of all failures.
     *
     * Shape: field/path → FailureInterface[].
     *
     * @return array<string, FailureInterface[]>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
