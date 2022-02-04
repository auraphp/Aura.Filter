<?php
namespace Aura\Filter;

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class ValueFilterTest extends TestCase
{
    protected $filter;

    protected function set_up()
    {
        $filter_factory = new FilterFactory();
        $this->filter = $filter_factory->newValueFilter();
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
}
