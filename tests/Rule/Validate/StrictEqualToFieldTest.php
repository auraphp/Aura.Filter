<?php
namespace Aura\Filter\Rule\Validate;

class StrictEqualToFieldTest extends AbstractValidateTest
{
    protected $other_field = 'other';

    protected $other_value = '1';

    protected function getSubject($value)
    {
        $subject = parent::getSubject($value);
        $subject->{$this->other_field} = $this->other_value;
        return $subject;
    }

    protected function getArgs()
    {
        $args = parent::getArgs();
        $args[] = 'other';
        return $args;
    }

    public function providerIs()
    {
        return array(
            array('1'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(1),
            array(true),
            array(1.00),
        );
    }

    public function testIs_fieldNotSet()
    {
        $subject = (object) array('foo' => '1');
        $rule = new StrictEqualToField();
        $this->assertFalse($rule->__invoke($subject, 'foo', 'no_such_field'));
    }
}
