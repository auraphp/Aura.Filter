<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Failure;

use JsonSerializable;

/**
 *
 * Represents the failure of a rule specification.
 *
 * @package Aura.Filter
 *
 */
class Failure implements JsonSerializable
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
     * @param string $message The failure message.
     *
     * @param array $args The arguments passed to the rule specification.
     *
     * @return self
     *
     */
    public function __construct(
        $field,
        $message,
        array $args = array()
    ) {
        $this->field = $field;
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

   /**
    *
    * Returns an array for json_encode.
    *
    * @return array
    *
    */
    public function jsonSerialize()
    {
        return array(
            'field' => $this->field,
            'message' => $this->message,
            'args' => $this->args,
        );
    }
}
