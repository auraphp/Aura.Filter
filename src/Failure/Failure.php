<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Failure;

use Aura\Filter_Interface\FailureInterface;

/**
 *
 * Represents the failure of a rule specification.
 *
 * @package Aura.Filter
 *
 */
class Failure implements FailureInterface
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
     *
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     *
     * Returns the failure message.
     *
     *
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     *
     * Returns the arguments passed to the rule specification.
     *
     *
     * @return mixed[]
     */
    public function getArgs(): array
    {
        return $this->args;
    }

   /**
     *
     * Returns an array for json_encode.
     *
     *
     * @return array<string, mixed[]>
     */
    public function jsonSerialize(): array
    {
        return array(
            'field' => $this->field,
            'message' => $this->message,
            'args' => $this->args,
        );
    }
}
