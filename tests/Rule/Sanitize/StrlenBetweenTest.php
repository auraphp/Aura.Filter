<?php
namespace Aura\Filter\Rule\Sanitize;

class StrlenBetweenTest extends AbstractSanitizeTest
{
    protected $min = 4;

    protected $max = 6;

    protected function getArgs()
    {
        return array($this->min, $this->max);
    }

    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('abc',         true, 'abc '),
            array('abcd',        true, 'abcd'),
            array('abcde',       true, 'abcde'),
            array('abcdef',      true, 'abcdef'),
            array('abcdefg',     true, 'abcdef'),
            array('abcdefgh',    true, 'abcdef'),
            array('абв',         true, 'абв '),
            array('абвг',        true, 'абвг'),
            array('абвгд',       true, 'абвгд'),
            array('абвгде',      true, 'абвгде'),
            array('абвгдеж',     true, 'абвгде'),
            array('абвгдежз',    true, 'абвгде'),
        );
    }
}
