<?php
namespace Aura\Filter\Rule\Sanitize;

abstract class AbstractSanitizeTest extends \PHPUnit_Framework_TestCase
{
    protected function getClass()
    {
        return substr(get_class($this), 0, -4);
    }

    protected function newRule()
    {
        $class = $this->getClass();
        $rule = new $class();
        return $rule;
    }

    protected function getArgs()
    {
        return array();
    }

    protected function getObject($value)
    {
        return (object) array('field' => $value);
    }

    protected function invoke($value)
    {
        $object = $this->getObject($value);
        $field = 'field';
        $args = array_merge(
            array($object, $field),
            (array) $this->getArgs()
        );
        $rule = $this->newRule();
        return array(
            call_user_func_array($rule, $args),
            $object->$field
        );
    }

    /**
     * @dataProvider providerTo
     */
    public function testTo($value, $expect_return, $expect_value)
    {
        list($actual_return, $actual_value) = $this->invoke($value);
        $this->assertSame($expect_return, $actual_return);
        $this->assertSame($expect_value, $actual_value);
    }

    abstract public function providerTo();
}
