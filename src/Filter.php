<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter;

use Aura\Filter\Exception;
use Aura\Filter\Failure\FailureCollection;
use Aura\Filter\Spec\SanitizeSpec;
use Aura\Filter\Spec\ValidateSpec;
use InvalidArgumentException;

/**
 *
 * A filter for an entire "subject" (i.e., an array or object).
 *
 * @package Aura.Filter
 *
 */
class Filter
{
    /**
     * Stop filtering on a field when a rule for that field fails.
     */
    const HARD_RULE = 'HARD_RULE';

    /**
     * Continue filtering on a field even when a rule for that field fails.
     */
    const SOFT_RULE = 'SOFT_RULE';

    /**
     * Stop filtering on all fields when a rule fails.
     */
    const STOP_RULE = 'STOP_RULE';

    /**
     *
     * An array of specifications for the filter subject.
     *
     * @var array
     *
     */
    protected $specs = array();

    /**
     *
     * Skip these fields on the filter subject.
     *
     * @var array
     *
     */
    protected $skip = array();

    /**
     *
     * A collection of failure objects.
     *
     * @var FailureCollection
     *
     */
    protected $failures;

    /**
     *
     * Use these field-specific messages when a subject field fails.
     *
     * @var array
     *
     */
    protected $field_messages = array();

    /**
     *
     * A prototype ValidateSpect.
     *
     * @var ValidateSpec
     *
     */
    protected $validate_spec;

    /**
     *
     * A prototype SanitizeSpect.
     *
     * @var SanitizeSpec
     *
     */
    protected $sanitize_spec;

    /**
     *
     * Operate in strict mode? (Subject fields without specifications are
     * failures for merely existing; all fields must have a specification.)
     *
     * @var bool
     *
     */
    protected $strict;

    /**
     *
     * The message to use when a field is present without a rule specified.
     *
     * @var string
     *
     */
    protected $strict_message = 'This field has no rule specified.';

    /**
     *
     * A prototype FailureCollection.
     *
     * @var FailureCollection
     *
     */
    protected $proto_failures;

    /**
     *
     * Constructor.
     *
     * @param ValidateSpec $validate_spec A prototype ValidateSpec.
     *
     * @param ValidateSpec $sanitize_spec A prototype SanitizeSpec.
     *
     * @param FailureCollection $failures A prototype FailureCollection.
     *
     * @return self
     *
     */
    public function __construct(
        ValidateSpec $validate_spec,
        SanitizeSpec $sanitize_spec,
        FailureCollection $failures
    ) {
        $this->validate_spec = $validate_spec;
        $this->sanitize_spec = $sanitize_spec;
        $this->proto_failures = $failures;
        $this->init();
    }

    /**
     *
     * Initialization logic for this filter.
     *
     * @return null
     *
     */
    protected function init()
    {
        // do nothing
    }

    /**
     *
     * Asserts that the subject passes the filter.
     *
     * @param array|object $subject The subject to be filtered.
     *
     * @return null
     *
     * @throws Exception\FilterFailed when the assertion fails.
     *
     */
    public function __invoke(&$subject)
    {
        return $this->assert($subject);
    }

    /**
     *
     * Asserts that the subject passes the filter.
     *
     * @param array|object $subject The subject to be filtered.
     *
     * @return null
     *
     * @throws Exception\FilterFailed when the assertion fails.
     *
     */
    public function assert(&$subject)
    {
        if ($this->apply($subject)) {
            return;
        }

        $class = get_class($this);
        $message = PHP_EOL
                 . "  Filter: {$class}" . PHP_EOL
                 . "  Fields:" . PHP_EOL
                 . $this->failures->getMessagesAsString('    ');

        $e = new Exception\FilterFailed($message);
        $e->setFilterClass($class);
        $e->setFailures($this->failures);
        $e->setSubject($subject);
        throw $e;
    }

    /**
     *
     * Adds a "validate" specification for a subject field.
     *
     * @param string $field The subject field name.
     *
     * @return ValidateSpec
     *
     */
    public function validate($field)
    {
        return $this->addSpec(clone $this->validate_spec, $field);
    }

    /**
     *
     * Adds a "sanitize" specification for a subject field.
     *
     * @param string $field The subject field name.
     *
     * @return SanitizeSpec
     *
     */
    public function sanitize($field)
    {
        return $this->addSpec(clone $this->sanitize_spec, $field);
    }

    /**
     *
     * Adds a specification for a subject field.
     *
     * @param AbstractSpec $spec The specification object.
     *
     * @param string $field The subject field name.
     *
     * @return AbstractSpec
     *
     */
    protected function addSpec($spec, $field)
    {
        $this->specs[] = $spec;
        $spec->field($field);
        return $spec;
    }

    /**
     *
     * Specifies a custom message to use when a subject field fails.
     *
     * @param string $field The subject field name.
     *
     * @param string $message The failure message to use.
     *
     * @return null
     *
     */
    public function useFieldMessage($field, $message)
    {
        $this->field_messages[$field] = $message;
    }

    /**
     *
     * Applies the filter to a subject.
     *
     * @param array|object $subject The subject to be filtered.
     *
     * @return bool True on success, false on failure.
     *
     */
    public function apply(&$subject)
    {
        if (is_array($subject)) {
            return $this->applyToArray($subject);
        }

        if (! is_object($subject)) {
            $type = gettype($subject);
            $message = "Apply the filter to an array or object, not a {$type}.";
            throw new InvalidArgumentException($message);
        }

        return $this->applyToObject($subject);
    }

    /**
     *
     * Should all fields have at least one rule?
     *
     * @param bool $strict All fields should have one rule, or not.
     *
     * @return null
     *
     */
    public function strict($strict = true)
    {
        $this->strict = $strict;
    }

    /**
     *
     * Applies the rule specifications to an array.
     *
     * @param array $array The filter subject.
     *
     * @return bool True if all rules passed, false if not.
     *
     */
    protected function applyToArray(&$array)
    {
        $object = (object) $array;
        $result = $this->applyToObject($object);
        $array = (array) $object;
        return $result;
    }

    /**
     *
     * Applies the rule specifications to an object.
     *
     * @param object $object The filter subject.
     *
     * @return bool True if all rules passed, false if not.
     *
     */
    protected function applyToObject($object)
    {
        $this->skip = array();
        $this->failures = clone $this->proto_failures;
        $this->applySpecs($object);
        $this->applyStrict($object);
        return $this->failures->isEmpty();
    }

    /**
     *
     * Applies the rule specifications to the subject.
     *
     * @param object $subject The filter subject.
     *
     * @return bool True if all rules passed, false if not.
     *
     */
    protected function applySpecs($subject)
    {
        foreach ($this->specs as $spec) {
            if ($this->skippedOrPassed($spec, $subject)) {
                continue;
            }
            if ($this->failed($spec) === self::STOP_RULE) {
                break;
            }
        }
    }

    /**
     *
     * Given a rule specification, is it to be skipped, or does the subject
     * pass?
     *
     * @param AbstractSpec $spec The rule specification.
     *
     * @param object $subject The filter subject.
     *
     * @return bool True if all rules passed, false if not.
     *
     */
    protected function skippedOrPassed($spec, $subject)
    {
        return isset($this->skip[$spec->getField()])
            || call_user_func($spec, $subject);
    }

    /**
     *
     * A rule specification failed.
     *
     * @param AbstractSpec $spec The failed rule specification.
     *
     * @return string The failure mode (hard, soft, or stop).
     *
     */
    protected function failed($spec)
    {
        $this->addFailure($spec);

        $field = $spec->getField();
        $failure_mode = $spec->getFailureMode();
        if ($failure_mode === self::HARD_RULE) {
            $this->skip[$field] = true;
        }

        return $failure_mode;
    }

    /**
     *
     * Adds a failure.
     *
     * @param AbstractSpec $spec The failed rule specification.
     *
     * @return Failure
     *
     */
    protected function addFailure($spec)
    {
        $field = $spec->getField();
        if (isset($this->field_messages[$field])) {
            return $this->failures->set($field, $this->field_messages[$field]);
        }

        return $this->failures->add($field, $spec->getMessage(), $spec->getArgs());
    }

    /**
     *
     * Make sure that all fields have a rule specification.
     *
     * @param object $subject The filter subject.
     *
     * @return null
     *
     */
    protected function applyStrict($subject)
    {
        if (! $this->strict) {
            return;
        }

        $fields = get_object_vars($subject);
        foreach ($this->specs as $spec) {
            unset($fields[$spec->getField()]);
        }

        foreach ($fields as $field => $value) {
            $this->failures->set($field, $this->strict_message);
        }
    }

    /**
     *
     * Returns the failures.
     *
     * @return FailureCollection
     *
     */
    public function getFailures()
    {
        return $this->failures;
    }
}
