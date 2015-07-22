<?php
namespace Aura\Filter\Rule\Sanitize;

class StrlenTest extends AbstractSanitizeTest
{
    protected $len = 4;

    protected function getArgs()
    {
        return array($this->len);
    }

    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('a', true, 'a   '),
            array('abcd', true, 'abcd'),
            array('abcdef', true, 'abcd'),
            array('ж', true, 'ж   '),
            array('абвг', true, 'абвг'),
            array('абвгде', true, 'абвг'),
        );
    }
}
