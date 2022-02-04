<?php
namespace Aura\Filter\Locator;

use Aura\Filter\Rule\Validate;

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class LocatorTest extends TestCase
{
    public function test__constructAndGet()
    {
        $fake_locator = new FakeLocator(array(
            'alpha' => function () { return new Validate\Alpha(); },
        ));

        $expect = 'Aura\Filter\Rule\Validate\Alpha';
        $actual = $fake_locator->get('alpha');
        $this->assertInstanceOf($expect, $actual);
    }

    public function testSetAndGet()
    {
        $fake_locator = new FakeLocator();
        $fake_locator->set('alpha', function () {
            return new Validate\Alpha;
        });

        $expect = 'Aura\Filter\Rule\Validate\Alpha';
        $actual = $fake_locator->get('alpha');
        $this->assertInstanceOf($expect, $actual);
    }

    public function testGet_noSuchHelper()
    {
        $fake_locator = new FakeLocator();
        $this->expectException('Aura\Filter\Exception\RuleNotMapped');
        $fake_locator->get('noSuchHelper');
    }
}
