<?php
declare(strict_types=1);

/**
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */

namespace Aura\Filter\Spec;

use Aura\Filter\SubjectFilter;

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
     * @var SubjectFilter
     *
     * @access protected
     */
    protected $filter;

    /**
     * __construct
     *
     * @param SubjectFilter $filter The filter to apply to the sub subject
     *
     * @access public
     */
    public function __construct(SubjectFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Apply sub filter to sub subject
     *
     * @param mixed $subject parent subject
     *
     * @return bool
     *
     * @access public
     */
    public function __invoke($subject)
    {
        $field = $this->field;
        $field_values = isset($subject->$field) ? $subject->$field : [];
        $values =& $field_values;
        return $this->filter->apply($values);
    }

    /**
     * Get the Subject filter
     *
     *
     * @access public
     */
    public function filter(): SubjectFilter
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
