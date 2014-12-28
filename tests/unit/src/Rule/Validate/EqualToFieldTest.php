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

    // public function testRuleIs_fieldNotSet()
    // {
    //     list($data, $field) = $this->getPrep('foo');
    //     $rule = $this->newRule($data, $field);
    //     $this->assertFalse($rule->is('no_such_field'));
    // }

    // public function testRuleIsNot_fieldNotSet()
    // {
    //     list($data, $field) = $this->getPrep('foo');
    //     $rule = $this->newRule($data, $field);
    //     $this->assertTrue($rule->isNot('no_such_field'));
    // }

    // public function testRuleFix_fieldNotSet()
    // {
    //     list($data, $field) = $this->getPrep('foo');
    //     $rule = $this->newRule($data, $field);
    //     $this->assertFalse($rule->fix('no_such_field'));
    // }
}
