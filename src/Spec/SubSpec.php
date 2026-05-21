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
