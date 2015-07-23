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
            array('абвг'),
            array('абвгабав'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array('a'),
            array('ab'),
            array('abc'),
            array('а'),
            array('аб'),
            array('абв'),
        );
    }
}
