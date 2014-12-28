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
        $this->filter = $filter_factory->newInstance();
    }

    public function testApply_softRule()
    {
        $this->filter->sanitize('field')->to('string');
        $this->filter->validate('field')->is('alnum')->asSoftRule();
        $this->filter->validate('field')->is('strlenMin', 6)->asHardRule();

        $object = (object) array('field' => 'foobar');
        $result = $this->filter->apply($object);
        $this->assertTrue($result);
        $expect = array();
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);

        $object = (object) array('field' => '!@#');
        $result = $this->filter->apply($object);
        $this->assertFalse($result);
        $expect = array(
            'field' => array(
                'field should have validated as alnum',
                'field should have validated as strlenMin(6)',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function testApply_notAnObject()
    {
        $this->setExpectedException('InvalidArgumentException');
        $object = 'string';
        $this->filter->apply($object);
    }

    public function testApply_hardRule()
    {
        $this->filter->validate('field')->is('alnum')->asHardRule();
        $this->filter->validate('field')->is('strlenMin', 6)->asHardRule();

        $object = (object) array('field' => '!@#');
        $result = $this->filter->apply($object);
        $this->assertFalse($result);

        $expect = array(
            'field' => array(
                'field should have validated as alnum',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);

        $actual = $this->filter->getMessages('field');
        $expect = array(
            'field should have validated as alnum',
        );
        $this->assertSame($expect, $actual);

        $expect = array();
        $actual = $this->filter->getMessages('no-such-field');
        $this->assertSame($expect, $actual);
    }

    public function testApply_stopRule()
    {
        $this->filter->validate('field1')->is('alnum')->asSoftRule();
        $this->filter->validate('field1')->is('strlenMin', 6)->asStopRule();
        $this->filter->validate('field2')->is('alnum');
        $this->filter->validate('field2')->is('strlenMin', 6);

        $object = (object) array('field1' => '!@#', 'field2' => 'abcdef');
        $result = $this->filter->apply($object);
        $this->assertFalse($result);

        $expect = array(
            'field1' => array(
                'field1 should have validated as alnum',
                'field1 should have validated as strlenMin(6)',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function testUseFieldMessage()
    {
        $this->filter->validate('field')->isNot('blank')->asSoftRule();
        $this->filter->validate('field')->is('alnum')->asSoftRule();
        $this->filter->validate('field')->is('strlenMin', 6)->asSoftRule();

        $object = (object) array('field' => '');
        $result = $this->filter->apply($object);
        $this->assertFalse($result);
        $expect = array(
            'field' => array(
                'field should not have validated as blank',
                'field should have validated as alnum',
                'field should have validated as strlenMin(6)',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);

        $this->filter->useFieldMessage('field', 'Please use 6-12 alphanumeric characters.');
        $result = $this->filter->apply($object);
        $this->assertFalse($result);
        $expect = array(
            'field' => array(
                'Please use 6-12 alphanumeric characters.',
            ),
        );
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function test__invoke()
    {
        $this->filter->validate('field')->is('alnum')->asSoftRule();
        $this->filter->validate('field')->is('strlenMin', 6)->asHardRule();

        // check for success
        $object = (object) array('field' => 'foobar');
        $result = $this->filter->__invoke($object);
        $this->assertTrue($result);

        // check for failure
        try {

            $object = (object) array('field' => '');
            $this->filter->__invoke($object);
            $this->fail('Should have thrown an exception');

        } catch (Exception\FilterFailed $e) {

            $this->assertSame($object, $e->getFilterSubject());
            $this->assertSame('Aura\Filter\Filter', $e->getFilterClass());
            $expect = array(
                'field' => array(
                    'field should have validated as alnum',
                    'field should have validated as strlenMin(6)',
                ),
            );
            $this->assertSame($expect, $e->getFilterMessages());
        }
    }

    public function testApply_onArray()
    {
        $this->filter->sanitize('field')->to('strlenMax', 3);
        $array = array('field' => '123456');
        $result = $this->filter->apply($array);
        $this->assertTrue($result);
        $this->assertSame('123', $array['field']);
    }

    public function test__invoke_onArray()
    {
        $this->filter->sanitize('field')->to('strlenMax', 3);
        $array = array('field' => '123456');
        $result = $this->filter->__invoke($array);
        $this->assertTrue($result);
        $this->assertSame('123', $array['field']);
    }
}
