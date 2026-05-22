<?php
namespace Aura\Filter;

use PHPUnit\Framework\TestCase;

class SubjectFilterTest extends TestCase
{
    protected SubjectFilter $filter;

    protected function setUp(): void
    {
        $filter_factory = new FilterFactory();
        $this->filter = $filter_factory->newSubjectFilter();
    }

    public function testApply_softRule(): void
    {
        $this->filter->sanitize('foo')->to('string');
        $this->filter->validate('foo')->is('alnum')->asSoftRule();
        $this->filter->validate('foo')->is('strlenMin', 6)->asHardRule();

        $subject = (object) ['foo' => 'foobar'];
        $result = $this->filter->apply($subject);
        $this->assertTrue($result->isSuccess());
        $this->assertSame([], $result->getFailures()->getMessages());

        $subject = (object) ['foo' => '!@#'];
        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());
        $expect = [
            'foo' => [
                'foo should have validated as alnum',
                'foo should have validated as strlenMin(6)',
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());
    }

    public function testApply_notAnObject(): void
    {
        // PHP throws TypeError at the boundary for non-array/non-object input.
        $this->expectException(\TypeError::class);
        $subject = 'string';
        $this->filter->apply($subject);
    }

    public function testApply_hardRule(): void
    {
        $this->filter->validate('foo')->is('alnum')->asHardRule();
        $this->filter->validate('foo')->is('strlenMin', 6)->asHardRule();

        $subject = (object) ['foo' => '!@#'];
        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());

        $expect = [
            'foo' => [
                'foo should have validated as alnum',
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());

        $expect = ['foo should have validated as alnum'];
        $this->assertSame($expect, $result->getFailures()->getMessagesForField('foo'));

        $this->assertSame([], $result->getFailures()->getMessagesForField('no-such-field'));
    }

    public function testApply_stopRule(): void
    {
        $this->filter->validate('foo1')->is('alnum')->asSoftRule();
        $this->filter->validate('foo1')->is('strlenMin', 6)->asStopRule();
        $this->filter->validate('foo2')->is('alnum');
        $this->filter->validate('foo2')->is('strlenMin', 6);

        $subject = (object) ['foo1' => '!@#', 'foo2' => 'abcdef'];
        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());

        $expect = [
            'foo1' => [
                'foo1 should have validated as alnum',
                'foo1 should have validated as strlenMin(6)',
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());
    }

    public function testApply_missingField(): void
    {
        $this->filter->validate('foo1')->is('alnum')->asSoftRule();
        $this->filter->validate('foo1')->is('strlenMin', 6)->asSoftRule();
        $this->filter->validate('foo2')->is('alnum');
        $this->filter->validate('foo2')->is('strlenMin', 6);

        $subject = (object) ['foo1' => '!@#', 'foo3' => null];
        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());

        $expect = [
            'foo1' => [
                'foo1 should have validated as alnum',
                'foo1 should have validated as strlenMin(6)',
            ],
            'foo2' => [
                'foo2 should have validated as alnum'
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());
    }

    public function testUseFieldMessage(): void
    {
        $this->filter->validate('foo')->isNotBlank()->asSoftRule();
        $this->filter->validate('foo')->is('alnum')->asSoftRule();
        $this->filter->validate('foo')->is('strlenMin', 6)->asSoftRule();

        $subject = (object) ['foo' => ''];
        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());
        $expect = [
            'foo' => [
                'foo should not have been blank',
                'foo should have validated as alnum',
                'foo should have validated as strlenMin(6)',
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());

        $this->filter->useFieldMessage('foo', 'Please use 6-12 alphanumeric characters.');
        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());
        $expect = [
            'foo' => [
                'Please use 6-12 alphanumeric characters.',
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());
    }

    public function test__invoke(): void
    {
        $this->filter->validate('foo')->is('alnum')->asSoftRule();
        $this->filter->validate('foo')->is('strlenMin', 6)->asHardRule();

        // check for success
        $subject = (object) ['foo' => 'foobar'];
        $result = $this->filter->__invoke($subject);
        $this->assertNull($result);

        // check for failure
        try {
            $subject = (object) ['foo' => ''];
            $this->filter->__invoke($subject);
            $this->fail('Should have thrown an exception');
        } catch (Exception\FilterFailed $e) {
            $this->assertSame($subject, $e->getSubject());
            $this->assertSame('Aura\Filter\SubjectFilter', $e->getFilterClass());
            $expect = [
                'foo' => [
                    'foo should have validated as alnum',
                    'foo should have validated as strlenMin(6)',
                ],
            ];
            $this->assertSame($expect, $e->getFailures()->getMessages());
        }
    }

    public function testApply_onArray(): void
    {
        $this->filter->sanitize('foo')->to('strlenMax', 3);
        $this->filter->sanitize('bar')->to('remove');
        $array = ['foo' => '123456', 'bar' => 'remove-me'];
        $result = $this->filter->apply($array);
        $this->assertTrue($result->isSuccess());
        // apply() is stateless — sanitized values are in getValues(), not in $array
        $expect = ['foo' => '123'];
        $this->assertSame($expect, $result->getValues());
    }

    public function test__invoke_onArray(): void
    {
        $this->filter->sanitize('foo')->to('strlenMax', 3);
        $array = ['foo' => '123456'];
        // __invoke() writes sanitized values back via &$subject
        $this->filter->__invoke($array);
        $this->assertSame('123', $array['foo']);
    }

    public function testGetMessageOnClosure(): void
    {
        $this->filter->validate('age')->is('callback', function ($s, $f) {
            return false;
        });

        $array = ['foo' => '123456'];
        $result = $this->filter->apply($array);
        $actual = $result->getFailures()->getMessages();
        $expect = [
            'age' => [
                'age should have validated as callback(*Closure*)',
            ],
        ];
        $this->assertSame($expect, $actual);
    }

    public function test_issue140_case1(): void
    {
        $this->filter->validate('first_name')->isNotBlank();
        $this->filter->validate('first_name')->is('alpha')->asStopRule();
        $this->filter->validate('password')->isNotBlank();

        $subject = (object) ['first_name' => '888'];

        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());

        $expect = [
            'first_name' => [
                'first_name should have validated as alpha',
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());
    }

    public function test_issue140_case2(): void
    {
        $this->filter->validate('first_name')->isNotBlank();
        $this->filter->validate('first_name')->is('alpha')->asStopRule();
        $this->filter->validate('password')->isNotBlank();

        $subject = (object) ['first_name' => ''];

        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());

        $expect = [
            'first_name' => [
                'first_name should not have been blank',
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());
    }

    public function test_issue140_case2_multiple_stop_call(): void
    {
        $this->filter->validate('first_name')->isNotBlank()->asStopRule();
        $this->filter->validate('first_name')->is('alpha')->asStopRule();
        $this->filter->validate('password')->isNotBlank();

        $subject = (object) ['first_name' => ''];

        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());

        $expect = [
            'first_name' => [
                'first_name should not have been blank',
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());
    }

    public function test_issue140_case3(): void
    {
        $this->filter->validate('first_name')->isNotBlank()->is('alpha')->asStopRule();
        $this->filter->validate('password')->isNotBlank();

        $subject = (object) ['first_name' => ''];

        $result = $this->filter->apply($subject);
        $this->assertFalse($result->isSuccess());

        $expect = [
            'first_name' => [
                'first_name should have validated as alpha',
            ],
        ];
        $this->assertSame($expect, $result->getFailures()->getMessages());
    }

    // ------------------------------------------------------------------
    // subfilter + nested array tests
    // ------------------------------------------------------------------

    /**
     * A nested array field targeted by subfilter() is validated correctly;
     * failing nested fields surface their failures on the result.
     */
    public function testSubfilter_nestedArrayValidationFails(): void
    {
        $sub = $this->filter->subfilter('address');
        $sub->validate('city')->isNotBlank();
        $sub->validate('zip')->is('alnum');

        $data = ['address' => ['city' => '', 'zip' => '90210']];
        $result = $this->filter->apply($data);

        $this->assertFalse($result->isSuccess());
        $messages = $result->getFailures()->getMessages();
        $this->assertArrayHasKey('city', $messages);
    }

    /**
     * A nested array field targeted by subfilter() passes when all rules pass.
     */
    public function testSubfilter_nestedArrayValidationPasses(): void
    {
        $sub = $this->filter->subfilter('address');
        $sub->validate('city')->isNotBlank();
        $sub->validate('zip')->is('alnum');

        $data = ['address' => ['city' => 'Beverly Hills', 'zip' => '90210']];
        $result = $this->filter->apply($data);

        $this->assertTrue($result->isSuccess());
        $this->assertTrue($result->getFailures()->isEmpty());
    }

    /**
     * Sanitize rules inside a subfilter write sanitized values back via getValues().
     */
    public function testSubfilter_nestedArraySanitizeWritesBack(): void
    {
        $sub = $this->filter->subfilter('address');
        $sub->sanitize('city')->to('string');
        $sub->sanitize('zip')->to('strlenMax', 5);

        $data = ['address' => ['city' => 'Beverly Hills', 'zip' => '902101234']];
        $result = $this->filter->apply($data);

        $this->assertTrue($result->isSuccess());
        // apply() is stateless — sanitized values are in getValues()
        $values = $result->getValues();
        $this->assertSame('90210', $values['address']['zip']);
    }

    /**
     * Array fields without a subfilter are not converted to stdClass.
     */
    public function testSubfilter_plainArrayFieldUnaffectedBySubfilter(): void
    {
        $sub = $this->filter->subfilter('address');
        $sub->validate('city')->isNotBlank();

        $this->filter->validate('tags')->is('callback', function ($subject, $field) {
            return is_array($subject->$field);
        });

        $data = [
            'tags'    => ['php', 'aura'],
            'address' => ['city' => 'NYC'],
        ];
        $result = $this->filter->apply($data);

        $this->assertTrue($result->isSuccess());
    }

    /**
     * Deeply nested arrays (three levels) are handled correctly.
     */
    public function testSubfilter_deeplyNestedArray(): void
    {
        $sub     = $this->filter->subfilter('order');
        $subItem = $sub->subfilter('item');
        $subItem->validate('name')->isNotBlank();

        $data = ['order' => ['item' => ['name' => '']]];
        $result = $this->filter->apply($data);
        $this->assertFalse($result->isSuccess());

        // passing case
        $filter2 = (new FilterFactory())->newSubjectFilter();
        $s       = $filter2->subfilter('order');
        $s->subfilter('item')->validate('name')->isNotBlank();

        $data2 = ['order' => ['item' => ['name' => 'Widget']]];
        $this->assertTrue($filter2->apply($data2)->isSuccess());
    }

    /**
     * subfilter() on an object field (original pre-fix behaviour) still works.
     */
    public function testSubfilter_nestedObjectUnchanged(): void
    {
        $sub = $this->filter->subfilter('address');
        $sub->validate('city')->isNotBlank();

        $address        = new \stdClass();
        $address->city  = 'NYC';
        $subject        = new \stdClass();
        $subject->address = $address;

        $result = $this->filter->apply($subject);
        $this->assertTrue($result->isSuccess());
    }
}
