<?php
namespace Aura\Filter;

use Aura\Filter\Exception;

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

    protected $specs = array();

    protected $skip = array();

    protected $messages = array();

    protected $validate_spec;

    protected $sanitize_spec;

    public function __construct(
        ValidateSpec $validate_spec,
        SanitizeSpec $sanitize_spec
    ) {
        $this->validate_spec = $validate_spec;
        $this->sanitize_spec = $sanitize_spec;
        $this->init();
    }

    protected function init()
    {
        // do nothing
    }

    public function __invoke($object)
    {
        if (! $this->apply($object)) {
            $this->throwFailure($object);
        }
    }

    protected function throwFailure($object)
    {
        $class = get_class($this);
        $string = PHP_EOL . "  Filter: " . get_class($this) . PHP_EOL . "  Fields:";
        foreach ($this->getMessages() as $field => $messages) {
            foreach ($messages as $message) {
                $string .= PHP_EOL . "    {$field}: {$message}";
            }
        }

        $e = new Exception\FilterFailed($string);
        $e->setFilterClass($class);
        $e->setFilterMessages($this->getMessages());
        $e->setFilterSubject($subject);
        throw $e;
    }

    public function validate($object, $field, $field)
    {
        return $this->addSpec(
            clone $this->validate_spec,
            $field
        );
    }

    public function sanitize($field)
    {
        return $this->addSpec(
            clone $this->sanitize_spec,
            $field
        );
    }

    protected function addSpec($spec, $field)
    {
        $this->specs[] = $spec;
        $spec->field($field);
        return $spec;
    }

    public function apply($object)
    {
        $this->applySpecs($object);
        return ($this->messages) ? false : true;
    }

    protected function applySpecs($objects)
    {
        $this->skip = array();
        $this->messages = array();
        foreach ($this->specs as $spec) {
            if ($this->skippedOrPassed($spec, $object)) {
                continue;
            }
            if ($this->noteFailure($spec) === self::STOP_RULE) {
                break;
            }
        }
    }

    protected function skippedOrPassed($spec, $object)
    {
        return isset($this->skip[$spec->getField()])
            || call_user_func($spec, $object);
    }

    protected function noteFailure($spec)
    {
        $field = $spec->getField();
        $this->messages[$field][] = $spec->getMessage();

        $type = $spec->getType();
        if ($type === self::HARD_RULE) {
            $this->skip[$field] = true;
        }

        return $type;
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
