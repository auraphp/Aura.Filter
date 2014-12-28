<?php
namespace Aura\Filter\Rule\Validate;

abstract class AbstractValidateTest extends \PHPUnit_Framework_TestCase
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
        return call_user_func_array($rule, $args);
    }

    /**
     * @dataProvider providerIs
     */
    public function testIs($value)
    {
        $this->assertTrue($this->invoke($value));
    }

    /**
     * @dataProvider providerIsNot
     */
    public function testIsNot($value)
    {
        $this->assertFalse($this->invoke($value));
    }

    abstract public function providerIs();

    abstract public function providerIsNot();
}
