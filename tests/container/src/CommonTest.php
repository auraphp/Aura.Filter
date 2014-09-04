<?php
namespace Aura\Filter\_Config;

use Aura\Di\ContainerAssertionsTrait;

class CommonTest extends \PHPUnit_Framework_TestCase
{
    use ContainerAssertionsTrait;

    public function setUp()
    {
        $this->setUpContainer(array(
            'Aura\Filter\_Config\Common',
        ));
    }

    public function test()
    {
        $this->assertNewInstance('Aura\Filter\Rule\Any');
        $this->assertNewInstance('Aura\Filter\RuleCollection');
        $this->assertNewInstance('Aura\Filter\RuleLocator');
    }
}
