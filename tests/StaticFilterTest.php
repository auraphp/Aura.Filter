<?php
namespace Aura\Filter;

use Aura\Filter\Locator\ValidateLocator;
use Aura\Filter\Locator\SanitizeLocator;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class StaticFilterTest extends TestCase
{
    protected function set_up()
    {
        FakeStaticFilter::reset();
    }

    protected function tear_down()
    {
        FakeStaticFilter::reset();
    }

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

    public function testSetInstanceTwice()
    {
        FakeStaticFilter::setInstance(new ValueFilter(
            new ValidateLocator(),
            new SanitizeLocator()
        ));

        $this->expectException(
            'Aura\Filter\Exception',
            'Aura\Filter\FakeStaticFilter::$instance is already set.'
        );

        FakeStaticFilter::setInstance(new ValueFilter(
            new ValidateLocator(),
            new SanitizeLocator()
        ));
    }

    public function testValidateWithoutInstance()
    {
        $this->expectException(
            'Aura\Filter\Exception',
            'Aura\Filter\FakeStaticFilter::$instance not set.'
        );
        $value = 'foobar';
        FakeStaticFilter::validate($value, 'strlenMin', 6);
    }

    public function testSanitizeWithoutInstance()
    {
        $this->expectException(
            'Aura\Filter\Exception',
            'Aura\Filter\FakeStaticFilter::$instance not set.'
        );
        $value = 'foobar';
        FakeStaticFilter::sanitize($value, 'strlenMax', 3);
    }
}
