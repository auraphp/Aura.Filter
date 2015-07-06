<?php
namespace Aura\Filter\_Config;

use Aura\Di\_Config\AbstractContainerTest;

class ContainerTest extends AbstractContainerTest
{
    protected function getConfigClasses()
    {
        return array(
            'Aura\Filter\_Config\Common',
        );
    }

    protected function getAutoResolve()
    {
        return false;
    }

    public function provideNewInstance()
    {
        return array(
            array('Aura\Filter\Spec\SanitizeSpec'),
            array('Aura\Filter\Spec\ValidateSpec'),
            array('Aura\Filter\SubjectFilter'),
            array('Aura\Filter\ValueFilter'),
        );
    }
}
