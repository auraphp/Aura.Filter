<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Failure;

/**
 *
 * Represents the failure of a rule specification.
 *
 * @package Aura.Filter
 *
 */
class Failure
{
    /**
     *
     * The field that failed.
     *
     * @var string
     *
     */
    protected $field;

    /**
     *
     * The failure mode.
     *
     * @var string
     *
     */
    protected $mode;

    /**
     *
     * The failure message.
     *
     * @var string
     *
     */
    protected $message;

    /**
     *
     * The arguments passed to the rule specification.
     *
     * @var array
     *
     */
    protected $args = array();

    /**
     *
     * Constructor.
     *
     * @param string $field The field that failed.
     *
     * @param string $mode The failure mode.
     *
     * @param string $message The failure message.
     *
     * @param array $args The arguments passed to the rule specification.
     *
     * @return self
     *
     */
    public function __construct(
        $field,
        $mode,
        $message,
        array $args = array()
    ) {
        $this->field = $field;
        $this->mode = $mode;
        $this->message = $message;
        $this->args = $args;
    }

    /**
     *
     * Returns the field that failed.
     *
     * @return string
     *
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     *
     * Returns the failure mode.
     *
     * @return string
     *
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     *
     * Returns the failure message.
     *
     * @return string
     *
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     *
     * Returns the arguments passed to the rule specification.
     *
     * @return array
     *
     */
    public function getArgs()
    {
        return $this->args;
    }
}
