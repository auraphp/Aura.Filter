<?php
namespace Aura\Filter\Rule;

use Aura\Filter\Rule\Validate;

class RuleLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function test__constructAndGet()
    {
        $rule_locator = new RuleLocator(
            [
                'alpha' => function () {
                    return new Validate\Alpha;
                },
            ]
        );

        $expect = 'Aura\Filter\Rule\Validate\Alpha';
        $actual = $rule_locator->get('alpha');
        $this->assertInstanceOf($expect, $actual);
    }

    public function testSetAndGet()
    {
        $rule_locator = new RuleLocator;
        $rule_locator->set('alpha', function () {
            return new Validate\Alpha;
        });

        $expect = 'Aura\Filter\Rule\Validate\Alpha';
        $actual = $rule_locator->get('alpha');
        $this->assertInstanceOf($expect, $actual);
    }

    public function testGet_noSuchHelper()
    {
        $rule_locator = new RuleLocator;
        $this->setExpectedException('Aura\Filter\Exception\RuleNotMapped');
        $rule_locator->get('noSuchHelper');
    }
}
