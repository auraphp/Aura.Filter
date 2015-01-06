<?php
namespace Aura\Filter;

use Aura\Filter\Rule\RuleLocator;
use Aura\Filter\Rule\Sanitize;
use Aura\Filter\Rule\Validate;
use Aura\Filter\Spec\SanitizeSpec;
use Aura\Filter\Spec\ValidateSpec;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    protected $filter;

    protected function setUp()
    {
        $filter_factory = new FilterFactory();
        $this->filter = $filter_factory->newFilter();
    }

    public function testApply_softRule()
    {
        $this->filter->sanitize('foo')->to('string');
        $this->filter->validate('foo')->is('alnum')->asSoftRule();
        $this->filter->validate('foo')->is('strlenMin', 6)->asHardRule();

        $subject = (object) array('foo' => 'foobar');
        $result = $this->filter->apply($subject);
        $this->assertTrue($result);
        $expect = array();
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);

        $subject = (object) array('foo' => '!@#');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);
        $expect = array(
            'foo' => array(
                'foo should have validated as alnum',
                'foo should have validated as strlenMin(6)',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function testApply_notAnObject()
    {
        $this->setExpectedException('InvalidArgumentException');
        $subject = 'string';
        $this->filter->apply($subject);
    }

    public function testApply_hardRule()
    {
        $this->filter->validate('foo')->is('alnum')->asHardRule();
        $this->filter->validate('foo')->is('strlenMin', 6)->asHardRule();

        $subject = (object) array('foo' => '!@#');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);

        $expect = array(
            'foo' => array(
                'foo should have validated as alnum',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);

        $actual = $this->filter->getMessages('foo');
        $expect = array(
            'foo should have validated as alnum',
        );
        $this->assertSame($expect, $actual);

        $expect = array();
        $actual = $this->filter->getMessages('no-such-field');
        $this->assertSame($expect, $actual);
    }

    public function testApply_stopRule()
    {
        $this->filter->validate('foo1')->is('alnum')->asSoftRule();
        $this->filter->validate('foo1')->is('strlenMin', 6)->asStopRule();
        $this->filter->validate('foo2')->is('alnum');
        $this->filter->validate('foo2')->is('strlenMin', 6);

        $subject = (object) array('foo1' => '!@#', 'foo2' => 'abcdef');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);

        $expect = array(
            'foo1' => array(
                'foo1 should have validated as alnum',
                'foo1 should have validated as strlenMin(6)',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function testUseFieldMessage()
    {
        $this->filter->validate('foo')->isNot('blank')->asSoftRule();
        $this->filter->validate('foo')->is('alnum')->asSoftRule();
        $this->filter->validate('foo')->is('strlenMin', 6)->asSoftRule();

        $subject = (object) array('foo' => '');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);
        $expect = array(
            'foo' => array(
                'foo should not have validated as blank',
                'foo should have validated as alnum',
                'foo should have validated as strlenMin(6)',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);

        $this->filter->useFieldMessage('foo', 'Please use 6-12 alphanumeric characters.');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);
        $expect = array(
            'foo' => array(
                'Please use 6-12 alphanumeric characters.',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function test__invoke()
    {
        $this->filter->validate('foo')->is('alnum')->asSoftRule();
        $this->filter->validate('foo')->is('strlenMin', 6)->asHardRule();

        // check for success
        $subject = (object) array('foo' => 'foobar');
        $result = $this->filter->__invoke($subject);
        $this->assertNull($result);

        // check for failure
        try {

            $subject = (object) array('foo' => '');
            $this->filter->__invoke($subject);
            $this->fail('Should have thrown an exception');

        } catch (Exception\FilterFailed $e) {

            $this->assertSame($subject, $e->getFilterSubject());
            $this->assertSame('Aura\Filter\Filter', $e->getFilterClass());
            $expect = array(
                'foo' => array(
                    'foo should have validated as alnum',
                    'foo should have validated as strlenMin(6)',
                ),
            );
            $this->assertSame($expect, $e->getFilterMessages());
        }
    }

    public function testApply_onArray()
    {
        $this->filter->sanitize('foo')->to('strlenMax', 3);
        $this->filter->sanitize('bar')->to('remove');
        $array = array('foo' => '123456', 'bar' => 'remove-me');
        $result = $this->filter->apply($array);
        $this->assertTrue($result);
        $expect = array('foo' => '123');
        $this->assertSame($expect, $array);
    }

    public function test__invoke_onArray()
    {
        $this->filter->sanitize('foo')->to('strlenMax', 3);
        $array = array('foo' => '123456');
        $result = $this->filter->__invoke($array);
        $this->assertNull($result);
        $this->assertSame('123', $array['foo']);
    }

    public function testStrict()
    {
        $this->filter->validate('foo')->is('strlenMax', 6);
        $this->filter->strict();

        $subject = (object) array(
            'foo' => 'foobar',
            'bar' => 'barbaz'
        );

        $result = $this->filter->apply($subject);
        $this->assertFalse($result);

        $expect = array(
            'bar' => array(
                'This field should not be present.',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }
}
