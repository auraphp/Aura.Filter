<?php
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
     * @var SubjectFitler
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
        $values =& $subject->$field;
        return $this->filter->apply($values);
    }

    /**
     * Get the Subject filter
     *
     * @return SubjectFilter
     *
     * @access public
     */
    public function filter()
    {
        return $this->filter;
    }

    /**
     * Returns the default failure message for this rule specification.
     *
     * @return array
     *
     * @access protected
     */
    protected function getDefaultMessage()
    {
        return $this->filter
            ->getFailures()
            ->getMessages();
    }
}
