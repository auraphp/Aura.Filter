<?php
namespace Aura\Filter;

use Aura\Filter\Locator\ValidateLocator;
use Aura\Filter\Locator\SanitizeLocator;

class StaticFilterTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        FakeStaticFilter::setInstance(new ValueFilter(
            new ValidateLocator(),
            new SanitizeLocator()
        ));

        $value = 'foobar';

        $actual = FakeStaticFilter::validate($value, 'strlenMin', 6);
        $this->assertTrue($actual);
        $this->assertSame('foobar', $value);

        $actual = FakeStaticFilter::sanitize($value, 'strlenMax', 3);
        $this->assertTrue($actual);
        $this->assertSame('foo', $value);
    }
}
