<?php

namespace Aura\Filter\Failure;

use Aura\Filter_Interface\FailureInterface;
use PHPUnit\Framework\TestCase;

class FailureCollectionTest extends TestCase
{
    protected FailureCollection $failures;

    protected function setUp(): void
    {
        $this->failures = new FailureCollection();
    }

    // -------------------------------------------------------------------------
    // isEmpty
    // -------------------------------------------------------------------------

    public function testIsEmptyReturnsTrueOnFreshCollection(): void
    {
        $this->assertTrue($this->failures->isEmpty());
    }

    public function testIsEmptyReturnsFalseAfterAdd(): void
    {
        $this->failures->add('name', 'Name is required');
        $this->assertFalse($this->failures->isEmpty());
    }

    // -------------------------------------------------------------------------
    // add / set
    // -------------------------------------------------------------------------

    public function testAddAccumulatesMultipleFailuresForSameField(): void
    {
        $this->failures->add('foo', 'message 1', ['bar' => 'baz']);
        $this->failures->add('foo', 'message 2', ['zim' => 'dib']);

        $actual = $this->failures->forField('foo');
        $this->assertCount(2, $actual);

        $this->assertInstanceOf(FailureInterface::class, $actual[0]);
        $this->assertSame('foo',       $actual[0]->getField());
        $this->assertSame('message 1', $actual[0]->getMessage());
        $this->assertSame(['bar' => 'baz'], $actual[0]->getArgs());

        $this->assertSame('message 2', $actual[1]->getMessage());
    }

    public function testSetReplacesAllPreviousFailuresForField(): void
    {
        $this->failures->add('foo', 'first');
        $this->failures->add('foo', 'second');
        $this->failures->set('foo', 'replaced');

        $actual = $this->failures->forField('foo');
        $this->assertCount(1, $actual);
        $this->assertSame('replaced', $actual[0]->getMessage());
    }

    public function testSetReturnsTheNewFailure(): void
    {
        $failure = $this->failures->set('foo', 'msg');
        $this->assertInstanceOf(FailureInterface::class, $failure);
        $this->assertSame('msg', $failure->getMessage());
    }

    // -------------------------------------------------------------------------
    // forField / forPath
    // -------------------------------------------------------------------------

    public function testForFieldReturnsEmptyArrayForUnknownField(): void
    {
        $this->assertSame([], $this->failures->forField('no-such-field'));
    }

    public function testForFieldWithDotNotationKey(): void
    {
        $this->failures->add('address.city', 'City is required');

        $actual = $this->failures->forField('address.city');
        $this->assertCount(1, $actual);
        $this->assertSame('City is required', $actual[0]->getMessage());
    }

    public function testForPathIsSameAsForField(): void
    {
        $this->failures->add('address.city', 'City is required');

        $this->assertSame(
            $this->failures->forField('address.city'),
            $this->failures->forPath('address.city')
        );
    }

    public function testForPathReturnsEmptyArrayForUnknownPath(): void
    {
        $this->assertSame([], $this->failures->forPath('address.city'));
    }

    public function testForPathWithThreeLevels(): void
    {
        $this->failures->add('orders.0.name', 'Name is required');

        $actual = $this->failures->forPath('orders.0.name');
        $this->assertCount(1, $actual);
        $this->assertSame('Name is required', $actual[0]->getMessage());
    }

    // -------------------------------------------------------------------------
    // getMessages
    // -------------------------------------------------------------------------

    public function testGetMessagesReturnsEmptyArrayWhenNoFailures(): void
    {
        $this->assertSame([], $this->failures->getMessages());
    }

    public function testGetMessagesWithFlatKeys(): void
    {
        $this->failures->add('name',  'Name is required');
        $this->failures->add('email', 'Email is required');

        $this->assertSame(
            [
                'name'  => ['Name is required'],
                'email' => ['Email is required'],
            ],
            $this->failures->getMessages()
        );
    }

    public function testGetMessagesWithDotNotationKeys(): void
    {
        // This is the form sub-filter failures arrive in after being prefixed
        // with the parent field name in SubjectFilter::failed().
        $this->failures->add('address.city', 'City is required');
        $this->failures->add('address.zip',  'Zip is required');
        $this->failures->add('shipping.city', 'Shipping city is required');

        $messages = $this->failures->getMessages();

        $this->assertSame(['City is required'],          $messages['address.city']);
        $this->assertSame(['Zip is required'],           $messages['address.zip']);
        $this->assertSame(['Shipping city is required'], $messages['shipping.city']);

        // Keys must be the full paths, not the bare child names.
        $this->assertArrayNotHasKey('city', $messages);
        $this->assertArrayNotHasKey('zip',  $messages);
    }

    public function testGetMessagesMultipleFailuresPerField(): void
    {
        $this->failures->add('name', 'Name is required');
        $this->failures->add('name', 'Name must be alpha');

        $this->assertSame(
            ['Name is required', 'Name must be alpha'],
            $this->failures->getMessages()['name']
        );
    }

    // -------------------------------------------------------------------------
    // getMessagesForField
    // -------------------------------------------------------------------------

    public function testGetMessagesForFieldReturnsStrings(): void
    {
        $this->failures->add('foo', 'message 1');
        $this->failures->add('foo', 'message 2');

        $this->assertSame(
            ['message 1', 'message 2'],
            $this->failures->getMessagesForField('foo')
        );
    }

    public function testGetMessagesForFieldReturnsEmptyArrayForUnknownField(): void
    {
        $this->assertSame([], $this->failures->getMessagesForField('nope'));
    }

    public function testGetMessagesForFieldWithDotNotationKey(): void
    {
        $this->failures->add('address.city', 'City is required');

        $this->assertSame(
            ['City is required'],
            $this->failures->getMessagesForField('address.city')
        );
    }

    // -------------------------------------------------------------------------
    // getMessagesAsString
    // -------------------------------------------------------------------------

    public function testGetMessagesAsStringFormatsAllFields(): void
    {
        $this->failures->add('name',  'Name is required');
        $this->failures->add('email', 'Email is required');

        $expected = 'name: Name is required' . PHP_EOL
                  . 'email: Email is required' . PHP_EOL;

        $this->assertSame($expected, $this->failures->getMessagesAsString());
    }

    public function testGetMessagesAsStringWithPrefix(): void
    {
        $this->failures->add('name', 'Name is required');

        $expected = '  name: Name is required' . PHP_EOL;
        $this->assertSame($expected, $this->failures->getMessagesAsString('  '));
    }

    public function testGetMessagesAsStringWithDotNotationKey(): void
    {
        $this->failures->add('address.city', 'City is required');

        $expected = 'address.city: City is required' . PHP_EOL;
        $this->assertSame($expected, $this->failures->getMessagesAsString());
    }

    public function testGetMessagesAsStringReturnsEmptyStringWhenNoFailures(): void
    {
        $this->assertSame('', $this->failures->getMessagesAsString());
    }

    // -------------------------------------------------------------------------
    // getMessagesForFieldAsString
    // -------------------------------------------------------------------------

    public function testGetMessagesForFieldAsString(): void
    {
        $this->failures->add('foo', 'message 1');
        $this->failures->add('foo', 'message 2');

        $expected = 'message 1' . PHP_EOL . 'message 2' . PHP_EOL;
        $this->assertSame($expected, $this->failures->getMessagesForFieldAsString('foo'));
    }

    public function testGetMessagesForFieldAsStringWithPrefix(): void
    {
        $this->failures->add('foo', 'message 1');

        $expected = '>> message 1' . PHP_EOL;
        $this->assertSame($expected, $this->failures->getMessagesForFieldAsString('foo', '>> '));
    }

    public function testGetMessagesForFieldAsStringReturnsEmptyStringForUnknownField(): void
    {
        $this->assertSame('', $this->failures->getMessagesForFieldAsString('nope'));
    }

    // -------------------------------------------------------------------------
    // getNestedMessages
    // -------------------------------------------------------------------------

    public function testGetNestedMessagesReturnsEmptyArrayWhenNoFailures(): void
    {
        $this->assertSame([], $this->failures->getNestedMessages());
    }

    public function testGetNestedMessagesFlatKey(): void
    {
        $this->failures->add('name', 'Name is required');

        $this->assertSame(
            ['name' => ['Name is required']],
            $this->failures->getNestedMessages()
        );
    }

    public function testGetNestedMessagesTwoLevels(): void
    {
        $this->failures->add('address.city', 'City is required');
        $this->failures->add('address.zip',  'Zip is required');

        $this->assertSame(
            [
                'address' => [
                    'city' => ['City is required'],
                    'zip'  => ['Zip is required'],
                ],
            ],
            $this->failures->getNestedMessages()
        );
    }

    public function testGetNestedMessagesThreeLevels(): void
    {
        $this->failures->add('orders.0.name', 'Name is required');

        $this->assertSame(
            [
                'orders' => [
                    '0' => [
                        'name' => ['Name is required'],
                    ],
                ],
            ],
            $this->failures->getNestedMessages()
        );
    }

    public function testGetNestedMessagesTwoSubfiltersWithSameChildKey(): void
    {
        // address.city and shipping.city must appear as distinct nested keys.
        $this->failures->add('address.city',  'Address city is required');
        $this->failures->add('shipping.city', 'Shipping city is required');

        $nested = $this->failures->getNestedMessages();

        $this->assertSame(['Address city is required'],  $nested['address']['city']);
        $this->assertSame(['Shipping city is required'], $nested['shipping']['city']);
        $this->assertArrayNotHasKey('city', $nested);
    }

    /**
     * When both a parent path ("address") and a child path ("address.city")
     * carry failures, the parent's own messages are stored under the reserved
     * "_messages" key so that neither overwrites the other.
     */
    public function testGetNestedMessagesParentAndChildPathBothFail(): void
    {
        // Parent added first, child added second.
        $this->failures->add('address', 'Address is invalid');
        $this->failures->add('address.city', 'City is required');

        $actual = $this->failures->getNestedMessages();

        $this->assertSame(
            ['Address is invalid'],
            $actual['address']['_messages'],
            'Parent-level messages must survive when a child path also has failures'
        );
        $this->assertSame(
            ['City is required'],
            $actual['address']['city'],
            'Child-level messages must survive when the parent path also has failures'
        );
    }

    /**
     * Insertion order must not affect the outcome — child before parent must
     * produce the same result as parent before child.
     */
    public function testGetNestedMessagesChildBeforeParentBothFail(): void
    {
        $this->failures->add('address.city', 'City is required');
        $this->failures->add('address', 'Address is invalid');

        $actual = $this->failures->getNestedMessages();

        $this->assertSame(
            ['Address is invalid'],
            $actual['address']['_messages'],
            'Parent-level messages must survive regardless of insertion order'
        );
        $this->assertSame(
            ['City is required'],
            $actual['address']['city'],
            'Child-level messages must survive regardless of insertion order'
        );
    }

    // -------------------------------------------------------------------------
    // jsonSerialize
    // -------------------------------------------------------------------------

    public function testIsJsonSerializable(): void
    {
        $this->failures->add('foo', 'message 1', ['bar' => 'baz']);
        $this->failures->add('foo', 'message 2', ['zim' => 'dib']);

        $json = json_encode($this->failures);
        $this->assertSame(
            '{"foo":[{"field":"foo","message":"message 1","args":{"bar":"baz"}},{"field":"foo","message":"message 2","args":{"zim":"dib"}}]}',
            $json
        );
    }

    public function testJsonSerializeWithDotNotationKey(): void
    {
        $this->failures->add('address.city', 'City is required');

        $decoded = json_decode(json_encode($this->failures), true);
        $this->assertArrayHasKey('address.city', $decoded);
        $this->assertSame('City is required', $decoded['address.city'][0]['message']);
    }
}
