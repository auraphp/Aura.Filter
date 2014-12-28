<?php
namespace Aura\Filter\Spec;

use Aura\Filter\Filter;
use Aura\Filter\Rule\Locator\SanitizeLocator;
use Aura\Filter\Rule\Sanitize;

class SanitizeSpecTest extends \PHPUnit_Framework_TestCase
{
    protected $spec;

    protected function setUp()
    {
        $this->spec = new SanitizeSpec(new SanitizeLocator(array(
            'strlen' => function () { return new Sanitize\Strlen; },
        )));
    }

    public function testGetField()
    {
        $this->spec->field('foo');
        $this->assertSame('foo', $this->spec->getField());
    }

    public function testFailureModes()
    {
        $this->assertSame(Filter::HARD_RULE, $this->spec->getFailureMode());

        $this->spec->asSoftRule('soft failure message');
        $this->assertSame(Filter::SOFT_RULE, $this->spec->getFailureMode());
        $this->assertSame('soft failure message', $this->spec->getMessage());

        $this->spec->asHardRule('hard failure message');
        $this->assertSame(Filter::HARD_RULE, $this->spec->getFailureMode());
        $this->assertSame('hard failure message', $this->spec->getMessage());

        $this->spec->asStopRule('stop failure message');
        $this->assertSame(Filter::STOP_RULE, $this->spec->getFailureMode());
        $this->assertSame('stop failure message', $this->spec->getMessage());
    }

    public function testTo()
    {
        $this->spec->field('foo')->to('strlen', 3);

        $object = (object) array('foo' => 'zimgir');
        $this->assertTrue($this->spec->__invoke($object));
        $this->assertSame($object->foo, 'zim');

        $object->foo = array();
        $this->assertFalse($this->spec->__invoke($object));
        $this->assertSame($object->foo, array());
    }

    public function testGetMessage()
    {
        $this->spec->field('foo')->to('strlen', 3);
        $expect = 'foo should have sanitized to strlen(3)';
        $this->assertSame($expect, $this->spec->getMessage());
    }

    public function testTo_allowBlank()
    {
        $this->spec->field('foo')->to('strlen', 3)->allowBlank();

        $object = (object) array();
        $this->assertTrue($this->spec->__invoke($object));

        $object->foo = null;
        $this->assertTrue($this->spec->__invoke($object));

        $object->foo = 'zimgir';
        $this->assertTrue($this->spec->__invoke($object));

        $object->foo = array();
        $this->assertFalse($this->spec->__invoke($object));
    }
}
