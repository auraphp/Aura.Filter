<?php
declare(strict_types=1);

/**
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */

namespace Aura\Filter\Spec;

use Aura\Filter_Interface\SubjectFilterInterface;

/**
 * A specification for a "sub" subject
 *
 * @package Aura.Filter
 *
 */
class SubSpec extends Spec
{
    /**
     * Subject Filter
     *
     * @var SubjectFilterInterface
     *
     * @access protected
     */
    protected $filter;

    /**
     * __construct
     *
     * @param SubjectFilterInterface $filter The filter to apply to the sub subject
     *
     * @access public
     */
    public function __construct(SubjectFilterInterface $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Apply sub filter to sub subject.
     *
     * If the field value is an array it is converted to a stdClass before
     * filtering so sub-filter rules can access nested values as properties,
     * then converted back afterwards so sanitized values are written through
     * to the caller. Fields that are not targeted by a subfilter are never
     * touched and continue to reach their own rules as arrays.
     *
     * @param mixed $subject parent subject
     *
     * @return bool
     *
     * @access public
     */
    public function __invoke($subject)
    {
        $field  = $this->field;
        $values =& $subject->$field;

        if (is_array($values)) {
            $obj    = $this->arrayToObject($values);
            $result = $this->filter->apply($obj);
            $values = $this->objectToArray($obj);
            return $result;
        }

        return $this->filter->apply($values);
    }

    /**
     * Recursively converts an array to a stdClass so nested values are
     * reachable as object properties inside the sub-filter.
     *
     * @param array $array
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
     * arrayToObject() (e.g. DTOs passed in the original input data) are
     * returned as-is to avoid incorrectly flattening them.
     *
     * @param object $obj
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

    /**
     * Get the Subject filter
     *
     *
     * @access public
     */
    public function filter(): SubjectFilterInterface
    {
        return $this->filter;
    }

    /**
     * Returns the default failure message for this rule specification.
     *
     *
     * @access protected
     * @return mixed[][]
     */
    protected function getDefaultMessage(): array
    {
        return $this->filter
            ->getFailures()
            ->getMessages();
    }
}
