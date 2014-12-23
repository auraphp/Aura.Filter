<?php
/**
 *
 * This file is part of the Aura project for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule;

/**
 *
 * Abstract Rule
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractRule implements RuleInterface
{
    /**
     *
     * The full set of data to be filtered.
     *
     * @var object
     *
     */
    protected $data;

    /**
     *
     * The field to be filtered within the data.
     *
     * @var string
     *
     */
    protected $field;

    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => '',
        'failure_is_not'        => '',
        'failure_is_blank_or'   => '',
        'failure_fix'           => '',
        'failure_fix_blank_or'  => '',
    ];

    /**
     *
     * The message key to use.
     *
     * @var string
     *
     */
    protected $message_key = 'failure_is';

    /**
     *
     * Params passed into the filter for validate/sanitize; generally used by
     * the message.
     *
     * @var array
     *
     */
    protected $params = [];

    /**
     * {@inheritdoc}
     */
    public function prep($data, $field)
    {
        $this->data = $data;
        $this->field = $field;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message_map[$this->message_key];
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     *
     * Sets the params passed into the filter.
     *
     * @param array $params The params passed into the filter.
     *
     * @return void
     *
     */
    protected function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     *
     * Sets the message key to be used.
     *
     * @param string $message_key The message key to be used.
     *
     * @return void
     *
     */
    protected function setMessageKey($message_key)
    {
        $this->message_key = $message_key;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        $field = $this->field;
        if (isset($this->data->$field)) {
            return $this->data->$field;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $field = $this->field;
        $this->data->$field = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function is()
    {
        $this->setMessageKey('failure_is');

        return call_user_func_array([$this, 'validate'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function isNot()
    {
        $this->setMessageKey('failure_is_not');

        return ! call_user_func_array([$this, 'validate'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function isBlankOr()
    {
        $this->setMessageKey('failure_is_blank_or');
        if ($this->isBlank()) {
            return true;
        }

        return call_user_func_array([$this, 'validate'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function fix()
    {
        $this->setMessageKey('failure_fix');

        return call_user_func_array([$this, 'sanitize'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function fixBlankOr()
    {
        $this->setMessageKey('failure_fix_blank_or');

        if ($this->isBlank()) {
            $this->setValue(null);

            return true;
        }

        return call_user_func_array([$this, 'sanitize'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    protected function isBlank()
    {
        $value = $this->getValue();

        // nulls are blank
        if (is_null($value)) {
            return true;
        }

        // non-strings are not blank: int, float, object, array, resource, etc
        if (! is_string($value)) {
            return false;
        }

        // strings that trim down to exactly nothing are blank
        return trim($value) === '';
    }
}
