<?php
namespace Aura\Filter\Rule\Validate;

class StrlenMaxTest extends AbstractValidateTest
{
    protected $max = 3;

    protected function getArgs()
    {
        return array($this->max);
    }

    public function providerIs()
    {
        return array(
            array('a'),
            array('ab'),
            array('abc'),
            array('а'),
            array('аб'),
            array('абв'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array('abcd'),
            array('abcdefg'),
            array('абвг'),
            array('абвгдеж'),
        );
    }
}
