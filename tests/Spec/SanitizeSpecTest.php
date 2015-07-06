<?php
namespace Aura\Filter\Spec;

use Aura\Filter\SubjectFilter;
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
        $this->assertSame(Spec::HARD_RULE, $this->spec->getFailureMode());

        $this->spec->asSoftRule('soft failure message');
        $this->assertSame(Spec::SOFT_RULE, $this->spec->getFailureMode());
        $this->assertSame('soft failure message', $this->spec->getMessage());

        $this->spec->asHardRule('hard failure message');
        $this->assertSame(Spec::HARD_RULE, $this->spec->getFailureMode());
        $this->assertSame('hard failure message', $this->spec->getMessage());

        $this->spec->asStopRule('stop failure message');
        $this->assertSame(Spec::STOP_RULE, $this->spec->getFailureMode());
        $this->assertSame('stop failure message', $this->spec->getMessage());
    }

    public function testTo()
    {
        $this->spec->field('foo')->to('strlen', 3);

        $subject = (object) array('foo' => 'zimgir');
        $this->assertTrue($this->spec->__invoke($subject));
        $this->assertSame($subject->foo, 'zim');

        $subject->foo = array();
        $this->assertFalse($this->spec->__invoke($subject));
        $this->assertSame($subject->foo, array());
    }

    public function testGetMessage()
    {
        $this->spec->field('foo')->to('strlen', 3);
        $expect = 'foo should have sanitized to strlen(3)';
        $this->assertSame($expect, $this->spec->getMessage());
    }

    public function testToBlankOr()
    {
        $this->spec->field('foo')->toBlankOr('strlen', 3);

        $subject = (object) array();
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = null;
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = 'zimgir';
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = array();
        $this->assertFalse($this->spec->__invoke($subject));
    }

    public function testToBlankOr_useBlankValue()
    {
        $this->spec->field('foo')->toBlankOr('strlen', 3)->useBlankValue('xxx');
        $subject = (object) array();
        $this->assertTrue($this->spec->__invoke($subject));
        $this->assertSame('xxx', $subject->foo);
    }
}
