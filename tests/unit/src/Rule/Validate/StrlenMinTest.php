<?php
namespace Aura\Filter\Rule\Validate;

class StrlenMinTest extends AbstractValidateTest
{
    protected $min = 4;

    protected function getArgs()
    {
        return array($this->min);
    }

    public function providerIs()
    {
        return array(
            array('abcd'),
            array('efghijkl'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array('a'),
            array('ab'),
            array('abc'),
        );
    }
}
