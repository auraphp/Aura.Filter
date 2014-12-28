<?php
namespace Aura\Filter\Rule\Validate;

class EqualToFieldTest extends AbstractValidateTest
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

    public function providerIs()
    {
        return [
            [1],
            ['1'],
            [true],
        ];
    }

    public function providerIsNot()
    {
        return [
            [0],
            ['2'],
            [false],
        ];
    }

    public function testIs_fieldNotSet()
    {
        $object = (object) array('field' => '1');
        $rule = new EqualToField();
        $this->assertFalse($rule->__invoke($object, 'field', 'no_such_field'));
    }
}
