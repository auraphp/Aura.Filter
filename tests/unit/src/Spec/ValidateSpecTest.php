<?php
namespace Aura\Filter\Spec;

use Aura\Filter\Rule\RuleLocator;
use Aura\Filter\Rule\Validate;

class ValidateSpecTest extends \PHPUnit_Framework_TestCase
{
    protected $spec;

    protected function setUp()
    {
        $this->spec = new ValidateSpec(new RuleLocator([
            'strlen' => function () { return new Validate\Strlen; },
        ]));
    }

    public function testIs()
    {
        $this->spec->field('foo')->is('strlen', 3);

        $object = (object) array('foo' => 'bar');
        $this->assertTrue($this->spec->__invoke($object));

        $object->foo = 'zimgir';
        $this->assertFalse($this->spec->__invoke($object));
    }

    public function testIsNot()
    {
        $this->spec->field('foo')->isNot('strlen', 3);

        $object = (object) array('foo' => 'bar');
        $this->assertFalse($this->spec->__invoke($object));

        $object->foo = 'doom';
        $this->assertTrue($this->spec->__invoke($object));
    }

    public function testGetMessage_is()
    {
        $this->spec->field('foo')->is('strlen', 3);
        $expect = 'foo should have validated as strlen(3)';
        $this->assertSame($expect, $this->spec->getMessage());
    }

    public function testGetMessage_isNot()
    {
        $this->spec->field('foo')->isNot('strlen', 3);
        $expect = 'foo should not have validated as strlen(3)';
        $this->assertSame($expect, $this->spec->getMessage());
    }
}
