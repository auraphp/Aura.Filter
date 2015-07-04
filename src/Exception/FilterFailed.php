<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Exception;

use Aura\Filter\Exception;
use Aura\Filter\Failure\FailureCollection;

/**
 *
 * One or more filter rules failed.
 *
 * @package Aura.Filter
 *
 */
class FilterFailed extends Exception
{
    /**
     *
     * Failures from the filter.
     *
     * @var FailureCollection
     *
     */
    protected $failures;

    /**
     *
     * The subject being filtered.
     *
     * @var mixed
     *
     */
    protected $subject;

    /**
     *
     * The class of the filter being applied.
     *
     * @var string
     *
     */
    protected $filter_class;

    /**
     *
     * Sets the class of the filter being applied.
     *
     * @param string $filter_class The filter class.
     *
     * @return null
     *
     */
    public function setFilterClass($filter_class)
    {
        $this->filter_class = $filter_class;
    }

    /**
     *
     * Gets the class of the filter being applied.
     *
     * @return string
     *
     */
    public function getFilterClass()
    {
        return $this->filter_class;
    }

    /**
     *
     * Sets the failures from the filter.
     *
     * @param FailureCollection $failures The filter failures.
     *
     * @return null
     *
     */
    public function setFailures(FailureCollection $failures)
    {
        $this->failures = $failures;
    }

    /**
     *
     * Gets the failures from the filter.
     *
     * @return FailureCollection
     *
     */
    public function getFailures()
    {
        return $this->failures;
    }

    /**
     *
     * Sets the subject of the filter.
     *
     * @param mixed $subject The subject being filtered.
     *
     * @return null
     *
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     *
     * Gets the subject of the filter.
     *
     * @return mixed
     *
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
