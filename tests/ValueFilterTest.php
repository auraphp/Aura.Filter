<?php
namespace Aura\Filter;

class ValueFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $filter;

    protected function setUp()
    {
        $filter_container = new FilterContainer();
        $this->filter = $filter_container->newValueFilter();
    }

    public function test()
    {
        $value = 'foobar';

        $actual = $this->filter->validate($value, 'strlenMin', 6);
        $this->assertTrue($actual);
        $this->assertSame('foobar', $value);

        $actual = $this->filter->sanitize($value, 'strlenMax', 3);
        $this->assertTrue($actual);
        $this->assertSame('foo', $value);
    }

    public function testAssert()
    {
        $this->assertNull($this->filter->assert(true, 'message'));

        $this->setExpectedException('Aura\Filter\Exception\ValueFailed', 'message', 96);
        $this->filter->assert(false, 'message', 96);
    }

    public function testSetExceptionClass()
    {
        $this->filter->setExceptionClass('DomainException');
        $this->setExpectedException('DomainException', 'message', 96);
        $this->filter->assert(false, 'message', 96);
    }
}
