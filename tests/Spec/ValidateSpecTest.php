<?php
namespace Aura\Filter\Spec;

use Aura\Filter\Filter;
use Aura\Filter\Locator\ValidateLocator;
use Aura\Filter\Rule\Validate;

class ValidateSpecTest extends \PHPUnit_Framework_TestCase
{
    protected $spec;

    protected function setUp()
    {
        $this->spec = new ValidateSpec(new ValidateLocator(array(
            'strlen' => function () { return new Validate\Strlen; },
        )));
    }

    public function testGetField()
    {
        $this->spec->field('foo');
        $this->assertSame('foo', $this->spec->getField());
    }

    public function testFailureModes()
    {
        $this->assertTrue($this->spec->isHardRule());

        $this->spec->asSoftRule('soft failure message');
        $this->assertTrue($this->spec->isSoftRule());
        $this->assertSame('soft failure message', $this->spec->getMessage());

        $this->spec->asHardRule('hard failure message');
        $this->assertTrue($this->spec->isHardRule());
        $this->assertSame('hard failure message', $this->spec->getMessage());

        $this->spec->asStopRule('stop failure message');
        $this->assertTrue($this->spec->isStopRule());
        $this->assertSame('stop failure message', $this->spec->getMessage());
    }

    public function testIs()
    {
        $this->spec->field('foo')->is('strlen', 3);

        $subject = (object) array('foo' => 'bar');
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = 'zimgir';
        $this->assertFalse($this->spec->__invoke($subject));
    }

    public function testIsNot()
    {
        $this->spec->field('foo')->isNot('strlen', 3);

        $subject = (object) array('foo' => 'bar');
        $this->assertFalse($this->spec->__invoke($subject));

        $subject->foo = 'doom';
        $this->assertTrue($this->spec->__invoke($subject));
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

    public function testIsBlank()
    {
        $this->spec->field('foo')->isBlank();

        $subject = (object) array();
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = null;
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = '  ';
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = 123;
        $this->assertFalse($this->spec->__invoke($subject));

        $expect = 'foo should have been blank';
        $actual = $this->spec->getMessage();
        $this->assertSame($expect, $actual);
    }

    public function testIsBlankOr()
    {
        $this->spec->field('foo')->isBlankOr('strlen', 3);

        $subject = (object) array();
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = null;
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = 123;
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = 'bar';
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = 'zimgir';
        $this->assertFalse($this->spec->__invoke($subject));
        $expect = 'foo should have been blank or have validated as strlen(3)';
        $actual = $this->spec->getMessage();
        $this->assertSame($expect, $actual);
    }

    public function testIsBlankOrNot()
    {
        $this->spec->field('foo')->isBlankOrNot('strlen', 3);

        $subject = (object) array();
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = null;
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = 123;
        $this->assertFalse($this->spec->__invoke($subject));
        $expect = 'foo should have been blank or not have validated as strlen(3)';
        $actual = $this->spec->getMessage();
        $this->assertSame($expect, $actual);

        $subject->foo = 'bar';
        $this->assertFalse($this->spec->__invoke($subject));
        $expect = 'foo should have been blank or not have validated as strlen(3)';
        $actual = $this->spec->getMessage();
        $this->assertSame($expect, $actual);

        $subject->foo = 'zimgir';
        $this->assertTrue($this->spec->__invoke($subject));
    }

    public function testIsNotBlank()
    {
        $this->spec->field('foo')->isNotBlank();

        $subject = (object) array();

        $subject->foo = 0;
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = '0';
        $this->assertTrue($this->spec->__invoke($subject));

        $subject->foo = 'bar';
        $this->assertTrue($this->spec->__invoke($subject));

        $expect = 'foo should not have been blank';

        $subject = (object) array();
        $this->assertFalse($this->spec->__invoke($subject));
        $this->assertSame($expect, $this->spec->getMessage());

        $subject->foo = null;
        $this->assertFalse($this->spec->__invoke($subject));
        $this->assertSame($expect, $this->spec->getMessage());

        $subject->foo = '  ';
        $this->assertFalse($this->spec->__invoke($subject));
        $this->assertSame($expect, $this->spec->getMessage());

        $subject->foo = 123;
        $this->assertTrue($this->spec->__invoke($subject));
    }
}
