<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Filter
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
    protected $filter_messages;

    protected $filter_subject;

    protected $filter_class;

    public function setFilterClass($filter_class)
    {
        $this->filter_class = $filter_class;
    }

    public function getFilterClass()
    {
        return $this->filter_class;
    }

    public function setFilterMessages(array $filter_messages)
    {
        $this->filter_messages = $filter_messages;
    }

    public function getFilterMessages()
    {
        return $this->filter_messages;
    }

    public function setFilterSubject($subject)
    {
        $this->filter_subject = $subject;
    }

    public function getFilterSubject()
    {
        return $this->filter_subject;
    }
}
