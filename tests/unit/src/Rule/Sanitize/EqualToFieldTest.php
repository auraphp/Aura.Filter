<?php
namespace Aura\Filter\Rule\Sanitize;

class EqualToFieldTest extends AbstractSanitizeTest
{
    protected $other_field = 'other';

    protected $other_value = '1';

    protected function getObject($value)
    {
        $object = parent::getObject($value);
        $object->{$this->other_field} = $this->other_value;
        return $object;
    }

    protected function getArgs()
    {
        $args = parent::getArgs();
        $args[] = 'other';
        return $args;
    }

    public function providerTo()
    {
        return array(
            array(0,         true, '1'),
            array(1,         true, '1'),
            array('1',       true, '1'),
            array(true,      true, '1'),
            array(false,     true, '1'),
        );
    }

    public function testTo_fieldNotSet()
    {
        $object = (object) array('field' => '1');
        $rule = new EqualToField();
        $this->assertFalse($rule->__invoke($object, 'field', 'no_such_field'));
    }
}
