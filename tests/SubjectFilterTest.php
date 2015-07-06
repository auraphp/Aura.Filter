<?php
namespace Aura\Filter;

class SubjectFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $filter;

    protected function setUp()
    {
        $filter_container = new FilterContainer();
        $this->filter = $filter_container->newSubjectFilter();
    }

    protected function assertFailureMessages($expect, $field = null)
    {
        $failures = $this->filter->getFailures();
        if ($field) {
            $actual = $failures->getMessagesForField($field);
        } else {
            $actual = $failures->getMessages();
        }
        $this->assertSame($expect, $actual);
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
        $this->assertFailureMessages($expect);

        $subject = (object) array('foo' => '!@#');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);
        $expect = array(
            'foo' => array(
                'foo should have validated as alnum',
                'foo should have validated as strlenMin(6)',
            ),
        );
        $this->assertFailureMessages($expect);
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
        $this->assertFailureMessages($expect);

        $expect = array(
            'foo should have validated as alnum',
        );
        $this->assertFailureMessages($expect, 'foo');

        $expect = array();
        $this->assertFailureMessages($expect, 'no-such-field');
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
        $this->assertFailureMessages($expect);
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
        $this->assertFailureMessages($expect);

        $this->filter->useFieldMessage('foo', 'Please use 6-12 alphanumeric characters.');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result);
        $expect = array(
            'foo' => array(
                'Please use 6-12 alphanumeric characters.',
            ),
        );
        $this->assertFailureMessages($expect);
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
}