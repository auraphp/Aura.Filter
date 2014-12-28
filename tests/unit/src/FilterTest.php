<?php
namespace Aura\Filter;

use Aura\Filter\Rule\RuleLocator;
use Aura\Filter\Rule\Validate;
use Aura\Filter\Spec\ValidateSpec;
use Aura\Filter\Spec\SanitizeSpec;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    protected $filter;

    protected function setUp()
    {
        $validate_spec = new ValidateSpec(new RuleLocator([
            'alnum'     => function () { return new Validate\Alnum; },
            'alpha'     => function () { return new Validate\Alpha; },
            'between'   => function () { return new Validate\Between; },
            'blank'     => function () { return new Validate\Blank; },
            'closure'   => function () { return new Validate\Closure; },
            'int'       => function () { return new Validate\Int; },
            'inKeys'    => function () { return new Validate\InKeys; },
            'inValues'  => function () { return new Validate\InValues; },
            'max'       => function () { return new Validate\Max; },
            'min'       => function () { return new Validate\Min; },
            'regex'     => function () { return new Validate\Regex; },
            'string'    => function () { return new Validate\String; },
            'strlen'    => function () { return new Validate\Strlen; },
            'strlenMin' => function () { return new Validate\StrlenMin; },
        ]));

        $sanitize_spec = new SanitizeSpec(new RuleLocator([]));

        $this->filter = new Filter($validate_spec, $sanitize_spec);
    }

    public function testApply_softRule()
    {
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
        $expect = [
            'field should have validated as alnum',
        ];
        $this->assertSame($expect, $actual);

        $expect = array();
        $actual = $this->filter->getMessages('no-such-field');
        $this->assertSame($expect, $actual);
    }

    public function testValues_stopRule()
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

            $expect = array(
                'field' => array(
                    'field should have validated as alnum',
                    'field should have validated as strlenMin(6)',
                ),
            );
            $actual = $e->getFilterMessages();
            $this->assertSame($expect, $actual);
        }
    }
}
