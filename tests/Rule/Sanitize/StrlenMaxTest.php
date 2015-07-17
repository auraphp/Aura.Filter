<?php
namespace Aura\Filter\Rule\Sanitize;

class StrlenMaxTest extends AbstractSanitizeTest
{
    protected $max = 3;

    protected function getArgs()
    {
        return array($this->max);
    }

    public function providerTo()
    {
        return array(
            array(array(),   false, array()),
            array('a',       true, 'a'),
            array('abc',     true, 'abc'),
            array('abcd',    true, 'abc'),
            array('abcdefg', true, 'abc'),
            array('ж',       true, 'ж'),
            array('абв',     true, 'абв'),
            array('абвг',    true, 'абв'),
            array('абвгдеж', true, 'абв'),
        );
    }
}
