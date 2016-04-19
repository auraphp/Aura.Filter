<?php

namespace Aura\Filter\Failure;

class FailureCollectionTest extends \PHPUnit_Framework_TestCase
{
    protected $failures;

    protected function setUp()
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
        $this->assertInstanceOf('Aura\Filter\Failure\Failure', $failure);
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
}
