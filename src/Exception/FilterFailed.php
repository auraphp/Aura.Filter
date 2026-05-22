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
use Aura\Filter_Interface\FailuresInterface;

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
     * Failures from the filter.
     */
    protected FailuresInterface $failures;

    /**
     * The subject being filtered.
     */
    protected mixed $subject;

    /**
     * The class of the filter being applied.
     */
    protected string $filter_class;

    public function setFilterClass(string $filter_class): void
    {
        $this->filter_class = $filter_class;
    }

    public function getFilterClass(): string
    {
        return $this->filter_class;
    }

    public function setFailures(FailuresInterface $failures): void
    {
        $this->failures = $failures;
    }

    public function getFailures(): FailuresInterface
    {
        return $this->failures;
    }

    public function setSubject(mixed $subject): void
    {
        $this->subject = $subject;
    }

    public function getSubject(): mixed
    {
        return $this->subject;
    }
}
