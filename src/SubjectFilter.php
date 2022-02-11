<?php
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
use InvalidArgumentException;

/**
 *
 * A filter for an entire "subject" (i.e., an array or object).
 *
 * @package Aura.Filter
 *
 */
class SubjectFilter
{
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
     * A prototype ValidateSpec.
     *
     * @var ValidateSpec
     *
     */
    protected $validate_spec;

    /**
     *
     * A prototype SanitizeSpec.
     *
     * @var SanitizeSpec
     *
     */
    protected $sanitize_spec;


    /**
     * Factory for Sub subject specifications
     *
     * @var SubSpecFactory
     *
     * @access protected
     */
    protected $sub_spec_factory;

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
     * @param SubSpecFactory $sub_spec_factory A factory for SubSpec
     *
     * @param FailureCollection $failures A prototype FailureCollection.
     */
    public function __construct(
        ValidateSpec $validate_spec,
        SanitizeSpec $sanitize_spec,
        SubSpecFactory $sub_spec_factory,
        FailureCollection $failures
    ) {
        $this->validate_spec = $validate_spec;
        $this->sanitize_spec = $sanitize_spec;
        $this->sub_spec_factory = $sub_spec_factory;
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
    protected function init(): void
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
    public function assert(&$subject): void
    {
        if ($this->apply($subject)) {
            return;
        }

        $class = get_class($this);
        $message = PHP_EOL
                 . "  Filter: {$class}" . PHP_EOL
                 . "  Fields:" . PHP_EOL
                 . $this->failures->getMessagesAsString('    ');

        $e = new FilterFailed($message);
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
     *
     */
    public function validate(string $field): Spec
    {
        return $this->addSpec(clone $this->validate_spec, $field);
    }

    /**
     *
     * Adds a "sanitize" specification for a subject field.
     *
     * @param string $field The subject field name.
     *
     *
     */
    public function sanitize(string $field): Spec
    {
        return $this->addSpec(clone $this->sanitize_spec, $field);
    }

    /**
     *
     * Adds a "subfilter" specification for a subject field.
     *
     * @param string $field The subject field name.
     *
     *
     */
    public function subfilter(string $field, $class = 'Aura\Filter\SubjectFilter'): Spec
    {
        $spec = $this->sub_spec_factory->newSubSpec($class);
        return $this->addSpec($spec, $field);
    }

    /**
     *
     * Adds a specification for a subject field.
     *
     * @param Spec $spec The specification object.
     *
     * @param string $field The subject field name.
     *
     *
     */
    protected function addSpec(Spec $spec, string $field): Spec
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
    public function useFieldMessage(string $field, string $message): void
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
    public function apply(&$subject): bool
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
     * Applies the rule specifications to an array.
     *
     * @param array $array The filter subject.
     *
     * @return bool True if all rules passed, false if not.
     *
     */
    protected function applyToArray(array &$array): bool
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
     *
     * @return bool True if all rules passed, false if not.
     *
     */
    protected function applyToObject(object $object): bool
    {
        $this->skip = array();
        $this->failures = clone $this->proto_failures;
        foreach ($this->specs as $spec) {
            $continue = $this->applySpec($spec, $object);
            if (! $continue) {
                break;
            }
        }
        return $this->failures->isEmpty();
    }

    /**
     *
     * Apply a rule specification to the subject.
     *
     * @param Spec $spec The rule specification.
     *
     *
     * @return bool True to continue, false to stop.
     *
     */
    protected function applySpec(Spec $spec, object $subject): bool
    {
        if (isset($this->skip[$spec->getField()])) {

            // Issue 140 . Some rule already failed for the field.
            // Check the current one have a stop rule or not.
            if ($spec->isStopRule()) {
                return false;
            }

            return true;
        }

        if (call_user_func($spec, $subject)) {
            return true;
        }

        $this->failed($spec);

        if ($spec->isStopRule()) {
            return false;
        }

        return true;
    }

    /**
     *
     * Adds a failure.
     *
     * @param Spec $spec The failed rule specification.
     *
     *
     */
    protected function failed(Spec $spec): Failure
    {
        $field = $spec->getField();

        if ($spec->isHardRule()) {
            $this->skip[$field] = true;
        }

        if (isset($this->field_messages[$field])) {
            return $this->failures->set($field, $this->field_messages[$field]);
        }

        return $this->failures->add($field, $spec->getMessage(), $spec->getArgs());
    }

    /**
     *
     * Returns the failures.
     *
     *
     */
    public function getFailures(): FailureCollection
    {
        return $this->failures;
    }
}
