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
     * Messages from the filter.
     *
     * @var array
     *
     */
    protected $filter_messages;

    /**
     *
     * The subject being filtered.
     *
     * @var mixed
     *
     */
    protected $filter_subject;

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
     * Sets the messages from the filter.
     *
     * @param array $filter_messages The filter messages.
     *
     * @return null
     *
     */
    public function setFilterMessages(array $filter_messages)
    {
        $this->filter_messages = $filter_messages;
    }

    /**
     *
     * Gets the messages from the filter.
     *
     * @return array
     *
     */
    public function getFilterMessages()
    {
        return $this->filter_messages;
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
    public function setFilterSubject($subject)
    {
        $this->filter_subject = $subject;
    }

    /**
     *
     * Gets the subject of the filter.
     *
     * @return mixed
     *
     */
    public function getFilterSubject()
    {
        return $this->filter_subject;
    }
}
