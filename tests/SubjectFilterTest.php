<?php
namespace Aura\Filter;

class SubjectFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $filter;

    protected function setUp()
    {
        $filter_factory = new FilterFactory();
        $this->filter = $filter_factory->newSubjectFilter();
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
        $actual = $this->filter->getFailures()->getMessages();
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
        $actual = $this->filter->getFailures()->getMessages();
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
        $actual = $this->filter->getFailures()->getMessages();
        $this->assertSame($expect, $actual);

        $expect = array(
            'foo should have validated as alnum',
        );
        $actual = $this->filter->getFailures()->getMessagesForField('foo');
        $this->assertSame($expect, $actual);

        $expect = array();
        $actual = $this->filter->getFailures()->getMessagesForField('no-such-field');
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
        $actual = $this->filter->getFailures()->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function testApply_missingField()
    {
        $this->filter->validate('foo1')->is('alnum')->asSoftRule();
        $this->filter->validate('foo1')->is('strlenMin', 6)->asSoftRule();
        $this->filter->validate('foo2')->is('alnum');
        $this->filter->validate('foo2')->is('strlenMin', 6);

        $subject = (object) array('foo1' => '!@#', 'foo3' => null);
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);

        $expect = array(
            'foo1' => array(
                'foo1 should have validated as alnum',
                'foo1 should have validated as strlenMin(6)',
            ),
            'foo2' => array(
                'foo2 should have validated as alnum'
            ),
        );
        $actual = $this->filter->getFailures()->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function testUseFieldMessage()
    {
        $this->filter->validate('foo')->isNotBlank()->asSoftRule();
        $this->filter->validate('foo')->is('alnum')->asSoftRule();
        $this->filter->validate('foo')->is('strlenMin', 6)->asSoftRule();

        $subject = (object) array('foo' => '');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);
        $expect = array(
            'foo' => array(
                'foo should not have been blank',
                'foo should have validated as alnum',
                'foo should have validated as strlenMin(6)',
            ),
        );
        $actual = $this->filter->getFailures()->getMessages();
        $this->assertSame($expect, $actual);

        $this->filter->useFieldMessage('foo', 'Please use 6-12 alphanumeric characters.');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);
        $expect = array(
            'foo' => array(
                'Please use 6-12 alphanumeric characters.',
            ),
        );
        $actual = $this->filter->getFailures()->getMessages();
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

            $this->assertSame($subject, $e->getSubject());
            $this->assertSame('Aura\Filter\SubjectFilter', $e->getFilterClass());
            $expect = array(
                'foo' => array(
                    'foo should have validated as alnum',
                    'foo should have validated as strlenMin(6)',
                ),
            );

            $actual = $e->getFailures()->getMessages();
            $this->assertSame($expect, $actual);
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

    public function testGetMessageOnClosure()
    {
        $this->filter->validate('age')->is('callback', function($s, $f) {
            return false;
        });

        $array = array('foo' => '123456');
        $success = $this->filter->apply($array);
        $failures = $this->filter->getFailures();
        $actual = $failures->getMessages();
        $expect = array(
            'age' => array(
                'age should have validated as callback(*Closure*)',
            ),
        );
        $this->assertSame($actual, $expect);
    }
}
