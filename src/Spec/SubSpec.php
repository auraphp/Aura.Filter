<?php
declare(strict_types=1);

/**
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */

namespace Aura\Filter\Spec;

use Aura\Filter_Interface\FilterResultInterface;
use Aura\Filter_Interface\SubjectFilterInterface;

/**
 * A specification for a "sub" subject.
 *
 * @package Aura.Filter
 *
 */
class SubSpec extends Spec
{
    /**
     * The sub-filter to apply.
     */
    protected SubjectFilterInterface $filter;

    /**
     * The result of the last __invoke() call.
     */
    private ?FilterResultInterface $lastResult = null;

    /**
     * @param SubjectFilterInterface $filter The filter to apply to the sub subject.
     */
    public function __construct(SubjectFilterInterface $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Apply the sub-filter to the named field on the parent subject.
     *
     * The parent subject is always a stdClass working copy — assignment to
     * $subject->$field propagates the sanitized sub-values back into it.
     * Arrays are converted to stdClass before filtering and back afterwards
     * so sub-filter rules can access nested values as properties.
     *
     * Fields that are not targeted by a subfilter are never touched.
     */
    public function __invoke(object $subject): bool
    {
        $field  = $this->field;
        $values = $subject->$field;

        if (is_array($values)) {
            $obj               = $this->arrayToObject($values);
            $this->lastResult  = $this->filter->apply($obj);
            $subject->$field   = $this->objectToArray($this->lastResult->getValues());
        } else {
            $this->lastResult  = $this->filter->apply($values);
            $subject->$field   = $this->lastResult->getValues();
        }

        return $this->lastResult->isSuccess();
    }

    /**
     * Returns the result of the last __invoke() call, or null before first call.
     */
    public function getLastResult(): ?FilterResultInterface
    {
        return $this->lastResult;
    }

    /**
     * Returns the sub-filter for fluent chaining after subfilter() is called.
     */
    public function filter(): SubjectFilterInterface
    {
        return $this->filter;
    }

    /**
     * Recursively converts an array to a stdClass so nested values are
     * reachable as object properties inside the sub-filter.
     */
    private function arrayToObject(array $array): object
    {
        $obj = new \stdClass();
        foreach ($array as $key => $value) {
            $obj->$key = is_array($value) ? $this->arrayToObject($value) : $value;
        }
        return $obj;
    }

    /**
     * Recursively converts a stdClass back to an array so that sanitized
     * values are returned in the original data structure.
     *
     * Only stdClass nodes are converted — objects that were not created by
     * arrayToObject() (e.g. DTOs) are returned as-is.
     */
    private function objectToArray(object $obj): array
    {
        $arr = [];
        foreach ((array) $obj as $key => $value) {
            $arr[$key] = $value instanceof \stdClass
                ? $this->objectToArray($value)
                : $value;
        }
        return $arr;
    }
}
