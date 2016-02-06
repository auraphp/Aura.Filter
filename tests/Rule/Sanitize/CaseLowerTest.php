<?php
namespace Aura\Filter\Rule\Sanitize;

class CaseLowerTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('A', true, 'a'),
            array('AbCd', true, 'abcd'),
            array('ABCDEF', true, 'abcdef'),
            array('Ж', true, 'ж'),
            array('АБВГ', true, 'абвг'),
            array('АБВГДЕ', true, 'абвгде'),
        );
    }
}
