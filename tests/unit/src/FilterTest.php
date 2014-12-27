<?php
namespace Aura\Filter;

use Aura\Filter\Filter;
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

    public function testValidate()
    {
        $this->filter->addSoftRule('field1', Filter::IS, 'alnum');
        $this->filter->addHardRule('field1', Filter::IS, 'alpha');

        $this->filter->addSoftRule('field2', Filter::IS, 'alnum');
        $this->filter->addHardRule('field2', Filter::IS, 'alpha');

        $actual = $this->filter->getRules();
        $expect = [
            0 => [
                'field' => 'field1',
                'method' => 'is',
                'name' => 'alnum',
                'params' => [],
                'type' => Filter::SOFT_RULE,
            ],
            1 => [
                'field' => 'field1',
                'method' => 'is',
                'name' => 'alpha',
                'params' => [],
                'type' => Filter::HARD_RULE,
            ],
            2 => [
                'field' => 'field2',
                'method' => 'is',
                'name' => 'alnum',
                'params' => [],
                'type' => Filter::SOFT_RULE,
            ],
            3 => [
                'field' => 'field2',
                'method' => 'is',
                'name' => 'alpha',
                'params' => [],
                'type' => Filter::HARD_RULE,
            ],
        ];

        $this->assertSame($expect, $actual);
    }

    public function testApply()
    {
        $this->filter->addSoftRule('field', Filter::IS, 'alnum');
        $this->filter->addHardRule('field', Filter::IS, 'strlenMin', 6);

        $data = (object) ['field' => 'foobar'];
        $result = $this->filter->values($data);
        $this->assertTrue($result);
        $messages = $this->filter->getMessages();
        $this->assertTrue(empty($messages));
    }

    public function testValues_invalidArgument()
    {
        $this->setExpectedException('InvalidArgumentException');
        $data = 'string';
        $this->filter->values($data);
    }

    public function testValues_hardRule()
    {
        $this->filter->addHardRule('field', Filter::IS, 'alnum');
        $this->filter->addHardRule('field', Filter::IS, 'strlenMin', 6);

        $data = (object) ['field' => array()];
        $result = $this->filter->values($data);
        $this->assertFalse($result);

        $expect = [
            'field' => [
                'FILTER_RULE_FAILURE_IS_ALNUM',
            ],
        ];

        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);

        $actual = $this->filter->getMessages('field');
        $expect = [
            'FILTER_RULE_FAILURE_IS_ALNUM',
        ];
        $this->assertSame($expect, $actual);

        $expect = [];
        $actual = $this->filter->getMessages('no-such-field');
        $this->assertSame($expect, $actual);
    }

    public function testValues_softRule()
    {
        $this->filter->addSoftRule('field1', Filter::IS, 'alnum');
        $this->filter->addHardRule('field1', Filter::IS, 'strlenMin', 6);
        $this->filter->addHardRule('field1', Filter::FIX, 'string');
        $this->filter->addHardRule('field2', Filter::IS, 'int');
        $this->filter->addHardRule('field2', Filter::FIX, 'int');

        $data = (object) [
            'field1' => array(),
            'field2' => 88
        ];

        $result = $this->filter->values($data);
        $this->assertFalse($result);

        $expect = [
            'field1' => [
                'FILTER_RULE_FAILURE_IS_ALNUM',
                'FILTER_RULE_FAILURE_IS_STRLEN_MIN',
            ],
        ];

        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function testValues_stopRule()
    {
        $this->filter->addSoftRule('field1', Filter::IS, 'alnum');
        $this->filter->addStopRule('field1', Filter::IS, 'strlenMin', 6);
        $this->filter->addHardRule('field2', Filter::IS, 'int');

        $data = (object) ['field1' => array()];
        $result = $this->filter->values($data);
        $this->assertFalse($result);

        $expect = [
            'field1' => [
                'FILTER_RULE_FAILURE_IS_ALNUM',
                'FILTER_RULE_FAILURE_IS_STRLEN_MIN',
            ],
        ];

        $actual = $this->filter->getMessages();

        $this->assertSame($expect, $actual);
    }

    public function testValues_sanitizesInPlace()
    {
        $this->filter->addHardRule('field', Filter::FIX, 'string', 'foo', 'bar');
        $data = (object) ['field' => 'foo'];
        $result = $this->filter->values($data);
        $this->assertTrue($result);
        $this->assertSame($data->field, 'bar');
    }

    public function testValues_missingField()
    {
        $this->filter->addHardRule('field', Filter::IS, 'string');
        $data = (object) ['other_field' => 'foo']; // 'field' is missing
        $result = $this->filter->values($data);
        $this->assertFalse($result);
    }

    public function testValues_arraySanitizesInPlace()
    {
        $this->filter->addHardRule('field', Filter::FIX, 'string', 'foo', 'bar');
        $data = ['field' => 'foo'];
        $result = $this->filter->values($data);
        $this->assertTrue($result);
        $this->assertSame($data['field'], 'bar');
    }

    public function testUseFieldMessage()
    {
        $this->filter->addSoftRule('field1', Filter::IS, 'alnum');
        $this->filter->addHardRule('field1', Filter::IS, 'strlenMin', 6);
        $this->filter->addHardRule('field1', Filter::FIX, 'string');
        $this->filter->addHardRule('field2', Filter::IS, 'int');
        $this->filter->addHardRule('field2', Filter::FIX, 'int');
        $this->filter->useFieldMessage('field1', 'FILTER_FIELD_FAILURE_FIELD1');

        $data = (object) [
            'field1' => array(),
            'field2' => 88
        ];

        $result = $this->filter->values($data);
        $this->assertFalse($result);

        $expect = [
            'field1' => [
                'FILTER_FIELD_FAILURE_FIELD1',
            ],
        ];

        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function testFilteredArrayValue()
    {
        $this->filter->addSoftRule('field1', Filter::IS, 'inValues', ['foo', 'bar', 'baz']);
        $this->filter->addSoftRule('field2', Filter::IS, 'inKeys', ['foo', 'bar', 'baz']);

        $data = (object) [
            'field1' => 'zim',
            'field2' => 9,
        ];

        $result = $this->filter->values($data);
        $this->assertFalse($result);

        $expect = [
            'field1' => [
                'FILTER_RULE_FAILURE_IS_IN_VALUES',
            ],
            'field2' => [
                'FILTER_RULE_FAILURE_IS_IN_KEYS',
            ],
        ];
        $actual = $this->filter->getMessages();
        $this->assertSame($expect, $actual);
    }

    public function testRulesOnVirtualField()
    {
        $closure = function () {

            $check = checkdate(
                $this->data->dob_m,
                $this->data->dob_d,
                $this->data->dob_y
            );

            $dob = $this->data->dob_y . '-'
                 . $this->data->dob_m . '-'
                 . $this->data->dob_d;

            if ($check && date_create($dob)) {
                $this->setValue($dob);

                return true;
            }

            return false;
        };

        $this->filter->addSoftRule('dob', Filter::FIX, 'closure', $closure);

        $data = (object) [
            'dob_y' => '1979',
            'dob_m' => '11',
            'dob_d' => '07',
            'dob' => null,
        ];

        $result = $this->filter->values($data);
        $this->assertTrue($result);
        $this->assertSame('1979-11-07', $data->dob);

        $data = (object) [
            'dob_y' => '1979',
            'dob_m' => '02',
            'dob_d' => '29',
            'dob' => null,
        ];

        $result = $this->filter->values($data);
        $this->assertFalse($result);
        $this->assertNull($data->dob);
    }

    public function testSetRule()
    {
        // validate
        $this->filter->setRule('foo', 'Foo should be alpha only', function ($value) {
            return ctype_alpha($value);
        });

        // sanitize
        $this->filter->setRule('bar', 'Remove non-alpha from bar', function (&$value) {
            $value = preg_replace('/[^a-z]/i', '!', $value);

            return true;
        });

        // initial data
        $values = [
            'foo' => 'foo_value',
            'bar' => 'bar_value',
        ];

        // do the values pass all filters?
        $passed = $this->filter->values($values);

        // 'foo' is invalid
        $this->assertFalse($passed);

        // get just 'foo' messages
        $actual = $this->filter->getMessages('foo');
        $expect = [
            'Foo should be alpha only',
        ];
        $this->assertSame($expect, $actual);

        // should have changed the values on 'bar'
        $expect = [
            'foo' => 'foo_value',
            'bar' => 'bar!value',
        ];
        $this->assertSame($expect, $values);

        // let's make it valid
        $data['foo'] = 'foovalue';
        $passed = $this->filter->values($data);
        $this->assertTrue($passed);
    }

    public function testInstanceScript()
    {
        // Get instance
        $instance = (new FilterFactory())->newInstance();
        // Get the Rule Registry
        $factories = (new FilterFactory())->factories();

        // Check if the instance is a Filter Object
        $expect = 'Aura\Filter\Filter';
        $actual = $instance;
        $this->assertInstanceOf($expect, $actual);

        // Test if all normal Rules are present
        foreach ($factories as $name => $rule) {
            $expect = get_class($rule());
            $actual = $instance->getRuleLocator()->get($name);
            $this->assertInstanceOf($expect, $actual);
        }

        // Check if the Any Rule is present
        $expect = 'Aura\Filter\Rule\Any';
        $actual = $instance->getRuleLocator()->get('any');
        $this->assertInstanceOf($expect, $actual);
    }

    public function testNewRuleInAny()
    {
        $instance = (new FilterFactory())->newInstance();
        $any = $instance->getRuleLocator()->get('any');
        $any->getRuleLocator()->set('hex', function () {
            return new \Aura\Filter\Rule\Hex;
        });
        $instance->addSoftRule(
            'hexval',
            $instance::IS,
            'any',
            [
                ['hex']
            ]
        );
        $data = (object) [
            'hexval' => 'abcdef',
        ];
        $this->assertTrue($instance->values($data));
    }

    public function test__invoke()
    {
        $this->filter->addSoftRule('field', Filter::IS, 'alnum');
        $this->filter->addHardRule('field', Filter::IS, 'strlenMin', 6);

        // check for success
        $data = (object) ['field' => 'foobar'];
        $result = $this->filter->__invoke($data);
        $this->assertTrue($result);

        // check for failure
        try {
            $data = (object) ['field' => ''];
            $this->filter->__invoke($data);
            $this->fail('Should have thrown an exception');
        } catch (Exception\FilterFailed $e) {

            $this->assertSame($data, $e->getFilterSubject());

            $expect = array(
                'field' => array(
                    0 => 'FILTER_RULE_FAILURE_IS_ALNUM',
                    1 => 'FILTER_RULE_FAILURE_IS_STRLEN_MIN',
                ),
            );
            $actual = $e->getFilterMessages();
            $this->assertSame($expect, $actual);
        }
    }
}
