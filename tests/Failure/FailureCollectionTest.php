<?php

namespace Aura\Filter\Failure;

use PHPUnit\Framework\TestCase;

class FailureCollectionTest extends TestCase
{
    protected $failures;

    protected function setUp(): void
    {
        $this->failures = new FailureCollection();
    }

    public function testForField()
    {
        $this->failures->add('foo', 'message 1', array('bar' => 'baz'));
        $this->failures->add('foo', 'message 2', array('zim' => 'dib'));

        $this->assertCount(0, $this->failures->forField('no-such-field'));

        $actual = $this->failures->forField('foo');
        $this->assertCount(2, $actual);

        $failure = $actual[0];
        $this->assertInstanceOf(\Aura\Filter_Interface\FailureInterface::class, $failure);
        $this->assertSame('foo', $failure->getField());
        $this->assertSame('message 1', $failure->getMessage());
        $this->assertEquals(array('bar' => 'baz'), $failure->getArgs());

        $expect = 'message 1'.PHP_EOL.'message 2'.PHP_EOL;
        $actual = $this->failures->getMessagesForFieldAsString('foo');
        $this->assertSame($expect, $actual);
    }

    public function testIsJsonSerializable()
    {
        $this->failures->add('foo', 'message 1', array('bar' => 'baz'));
        $this->failures->add('foo', 'message 2', array('zim' => 'dib'));

        $json = json_encode($this->failures);
        $this->assertEquals('{"foo":[{"field":"foo","message":"message 1","args":{"bar":"baz"}},{"field":"foo","message":"message 2","args":{"zim":"dib"}}]}', $json);
    }

    public function testGetNestedMessagesSimple(): void
    {
        $this->failures->add('address.city', 'City is required');
        $this->failures->add('address.zip', 'Zip is required');

        $expected = [
            'address' => [
                'city' => ['City is required'],
                'zip'  => ['Zip is required'],
            ],
        ];
        $this->assertSame($expected, $this->failures->getNestedMessages());
    }

    /**
     * Both "address" (parent) and "address.city" (child) carry failures.
     * The tree builder must preserve both; neither may overwrite the other.
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
     * Same scenario but child is stored before parent — iteration order must not matter.
     */
    public function testGetNestedMessagesChildBeforeParentBothFail(): void
    {
        // Child added first, parent added second.
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
}
