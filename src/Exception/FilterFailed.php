<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
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
    public function setFilterClass(string $filter_class): void
    {
        $this->filter_class = $filter_class;
    }

    /**
     *
     * Gets the class of the filter being applied.
     *
     *
     */
    public function getFilterClass(): string
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
    public function setFailures(FailureCollection $failures): void
    {
        $this->failures = $failures;
    }

    /**
     *
     * Gets the failures from the filter.
     *
     *
     */
    public function getFailures(): FailureCollection
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
    public function setSubject($subject): void
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
