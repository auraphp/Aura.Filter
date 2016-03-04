<?php
namespace Aura\Filter\Rule\Sanitize;

class LowercaseTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('', true, ''),
            array('A', true, 'a'),
            array('AbCd', true, 'abcd'),
            array('ABCDEF', true, 'abcdef'),
            array('Ж', true, 'ж'),
            array('АБВГ', true, 'абвг'),
            array('АБВГДЕ', true, 'абвгде'),
        );
    }
}
