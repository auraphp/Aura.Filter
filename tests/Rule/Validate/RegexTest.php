<?php
namespace Aura\Filter\Rule\Validate;

class RegexTest extends AbstractValidateTest
{
    protected $expr_validate = '/^[\+\-]?[0-9]+$/';

    protected $expr_sanitize = '/[^a-z]/';

    protected function getArgs()
    {
        return array($this->expr_validate);
    }


    public function providerIs()
    {
        return array(
            array('+1234567890'),
            array(1234567890),
            array(-123456789.0),
            array(-1234567890),
            array('-123'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array(' '),
            array(''),
            array('-abc.123'),
            array('123.abc'),
            array('123),456'),
            array('0000123.456000'),
        );
    }
}
