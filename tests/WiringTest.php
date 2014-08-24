<?php
namespace Aura\Filter;

use Aura\Framework\Test\WiringAssertionsTrait;

class WiringTest extends \PHPUnit_Framework_TestCase
{
    use WiringAssertionsTrait;

    protected function setUp()
    {
        $this->loadDi();
    }

    public function testInstances()
    {
        $this->assertNewInstance('Aura\Filter\Rule\Any');
        $this->assertNewInstance('Aura\Filter\RuleCollection');
        $this->assertNewInstance('Aura\Filter\RuleLocator');
    }

    public function testTranslatedMessages()
    {
        $filter = $this->di->newInstance('Aura\Filter\RuleCollection');
        $filter->addSoftRule('foo', $filter::IS, 'any', [['alnum'], ['email']]);
        $object = (object) ['foo' => '!@#$'];
        $this->assertFalse($filter->values($object));
        $actual = $filter->getMessages();
        $expect = [
            'foo' => [
                'This field did not pass any of the sub-rules.',
            ],
        ];
        $this->assertSame($expect, $actual);
    }
}
