<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter;

use Aura\Filter\Exception\FilterFailed;
use Aura\Filter\Exception;
use Aura\Filter\Failure\Failure;
use Aura\Filter\Failure\FailureCollection;
use Aura\Filter\Spec\SanitizeSpec;
use Aura\Filter\Spec\Spec;
use Aura\Filter\Spec\ValidateSpec;
use Aura\Filter\Spec\SubSpecFactory;
use Aura\Filter\Spec\SubSpec;
use Aura\Filter_Interface\FailureInterface;
use Aura\Filter_Interface\FilterResult;
use Aura\Filter_Interface\FilterResultInterface;
use Aura\Filter_Interface\SubjectFilterInterface;
use InvalidArgumentException;

/**
 *
 * A filter for an entire "subject" (i.e., an array or object).
 *
 * @package Aura.Filter
 *
 */
class SubjectFilter implements SubjectFilterInterface
{
    /**
     * An array of specifications for the filter subject.
     *
     * @var Spec[]
     */
    protected array $specs = [];

    /**
     * Use these field-specific messages when a subject field fails.
     *
     * @var array<string, string>
     */
    protected array $field_messages = [];

    /**
     * A prototype ValidateSpec.
     */
    protected ValidateSpec $validate_spec;

    /**
     * A prototype SanitizeSpec.
     */
    protected SanitizeSpec $sanitize_spec;

    /**
     * Factory for Sub subject specifications.
     */
    protected SubSpecFactory $sub_spec_factory;

    /**
     * A prototype FailureCollection.
     */
    protected FailureCollection $proto_failures;

    /**
     * Constructor.
     */
    public function __construct(
        ValidateSpec $validate_spec,
        SanitizeSpec $sanitize_spec,
        SubSpecFactory $sub_spec_factory,
        FailureCollection $failures
    ) {
        $this->validate_spec    = $validate_spec;
        $this->sanitize_spec    = $sanitize_spec;
        $this->sub_spec_factory = $sub_spec_factory;
        $this->proto_failures   = $failures;
        $this->init();
    }

    /**
     * Initialization hook for subclasses.
     */
    protected function init(): void
    {
        // do nothing
    }

    /**
     * Assertion-callable shortcut: applies the filter, writes sanitized values
     * back into $subject on success, throws FilterFailed on failure.
     * Intentionally keeps &$subject so sanitized values are written back.
     *
     * @throws Exception\FilterFailed
     */
    public function __invoke(array|object &$subject): void
    {
        $result = $this->apply($subject);

        if (! $result->isSuccess()) {
            $class   = get_class($this);
            $message = PHP_EOL
                     . "  Filter: {$class}" . PHP_EOL
                     . "  Fields:" . PHP_EOL
                     . $result->getFailures()->getMessagesAsString('    ');
            $e = new FilterFailed($message);
            $e->setFilterClass($class);
            $e->setFailures($result->getFailures());
            $e->setSubject($subject);
            throw $e;
        }

        // Write sanitized values back into the caller's variable.
        $sanitized = $result->getValues();
        if (is_array($subject)) {
            $subject = $sanitized;
        } else {
            foreach (get_object_vars($sanitized) as $key => $value) {
                $subject->$key = $value;
            }
        }
    }

    /**
     * Asserts that the subject passes the filter.
     * Throws FilterFailed when the assertion fails.
     *
     * @throws Exception\FilterFailed
     */
    public function assert(array|object $subject): void
    {
        $result = $this->apply($subject);
        if ($result->isSuccess()) {
            return;
        }

        $class   = get_class($this);
        $message = PHP_EOL
                 . "  Filter: {$class}" . PHP_EOL
                 . "  Fields:" . PHP_EOL
                 . $result->getFailures()->getMessagesAsString('    ');

        $e = new FilterFailed($message);
        $e->setFilterClass($class);
        $e->setFailures($result->getFailures());
        $e->setSubject($subject);
        throw $e;
    }

    /**
     * Adds a "validate" specification for a subject field.
     */
    public function validate(string $field): Spec
    {
        return $this->addSpec(clone $this->validate_spec, $field);
    }

    /**
     * Adds a "sanitize" specification for a subject field.
     */
    public function sanitize(string $field): Spec
    {
        return $this->addSpec(clone $this->sanitize_spec, $field);
    }

    /**
     * Adds a "subfilter" specification for a subject field.
     * Returns the sub-filter for fluent chaining.
     */
    public function subfilter(string $field, string $subClass = ''): SubjectFilterInterface
    {
        $class = $subClass !== '' ? $subClass : static::class;
        $spec  = $this->sub_spec_factory->newSubSpec($class);
        $this->addSpec($spec, $field);
        return $spec->filter();
    }

    /**
     * Adds a specification for a subject field.
     */
    protected function addSpec(Spec $spec, string $field): Spec
    {
        $this->specs[] = $spec;
        $spec->field($field);
        return $spec;
    }

    /**
     * Specifies a custom message to use when a subject field fails.
     */
    public function useFieldMessage(string $field, string $message): void
    {
        $this->field_messages[$field] = $message;
    }

    /**
     * Applies the filter to a subject. Never mutates $values.
     * Returns a result containing the (sanitized) values and any failures.
     */
    public function apply(array|object $values): FilterResultInterface
    {
        if (is_array($values)) {
            return $this->applyToArray($values);
        }

        if (! is_object($values)) {
            $type = gettype($values);
            throw new InvalidArgumentException(
                "Apply the filter to an array or object, not a {$type}."
            );
        }

        return $this->applyToObject($values);
    }

    /**
     * Applies the rule specifications to an array.
     * Converts the array to an object, filters, converts back.
     */
    protected function applyToArray(array $array): FilterResult
    {
        $object = (object) $array;
        $result = $this->applyToObject($object);
        return new FilterResult(
            $result->isSuccess(),
            (array) $result->getValues(),
            $result->getFailures()
        );
    }

    /**
     * Applies the rule specifications to an object.
     * Works on a clone so the caller's original is never mutated.
     */
    protected function applyToObject(object $subject): FilterResult
    {
        $working = clone $subject;
        $ctx     = [
            'failures' => clone $this->proto_failures,
            'skip'     => [],
        ];

        foreach ($this->specs as $spec) {
            $continue = $this->applySpec($spec, $working, $ctx);
            if (! $continue) {
                break;
            }
        }

        return new FilterResult($ctx['failures']->isEmpty(), $working, $ctx['failures']);
    }

    /**
     * Applies a single rule specification to the subject.
     *
     * @param array{failures: FailureCollection, skip: array<string, bool>} $ctx
     */
    protected function applySpec(Spec $spec, object $subject, array &$ctx): bool
    {
        $field = $spec->getField();

        if (isset($ctx['skip'][$field])) {
            if ($spec->isStopRule()) {
                return false;
            }
            return true;
        }

        if (call_user_func($spec, $subject)) {
            return true;
        }

        $this->failed($spec, $subject, $ctx);

        if ($spec->isStopRule()) {
            return false;
        }

        return true;
    }

    /**
     * Records a failure for the given spec.
     *
     * @param array{failures: FailureCollection, skip: array<string, bool>} $ctx
     */
    protected function failed(Spec $spec, object $subject, array &$ctx): FailureInterface
    {
        $field = $spec->getField();

        if ($spec->isHardRule()) {
            $ctx['skip'][$field] = true;
        }

        if (isset($this->field_messages[$field])) {
            return $ctx['failures']->set($field, $this->field_messages[$field]);
        }

        // Sub-filters carry their own failures keyed by sub-field names.
        // Propagate those into the parent prefixed with the parent field so
        // callers can resolve them by full dot-notation path
        // (e.g. 'address.city' not just 'city').  Without the prefix,
        // failures from two different subfilters that share a child key
        // (e.g. address.city and shipping.city) would collapse onto the same
        // key and become indistinguishable.
        if ($spec instanceof SubSpec) {
            $lastResult  = $spec->getLastResult();
            $lastFailure = null;

            if ($lastResult !== null) {
                foreach ($lastResult->getFailures()->getMessages() as $subField => $messages) {
                    foreach ($messages as $message) {
                        $lastFailure = $ctx['failures']->add($field . '.' . $subField, $message);
                    }
                }
            }

            if ($lastFailure !== null) {
                return $lastFailure;
            }

            // Fallback: sub-filter failed but reported no individual failures.
            return $ctx['failures']->add($field, 'subfilter failed');
        }

        return $ctx['failures']->add($field, $spec->getMessage(), $spec->getArgs());
    }
}
