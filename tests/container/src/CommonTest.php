<?php
namespace Aura\Filter\_Config;

use Aura\Di\_Config\AbstractContainerTest;

class CommonTest extends AbstractContainerTest
{
    protected function getConfigClasses()
    {
        return array(
            'Aura\Filter\_Config\Common',
        );
    }

    public function provideNewInstance()
    {
        return array(
            array('Aura\Filter\Rule\Any'),
            array('Aura\Filter\RuleCollection'),
            array('Aura\Filter\RuleLocator'),
        );
    }
}
