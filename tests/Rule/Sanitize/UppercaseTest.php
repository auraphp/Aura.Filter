<?php
namespace Aura\Filter\Rule\Sanitize;

class UppercaseTest extends AbstractSanitizeTest
{
    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('', true, ''),
            array('a', true, 'A'),
            array('Ab cd', true, 'AB CD'),
            array('ABC DEF', true, 'ABC DEF'),
            array('ж', true, 'Ж'),
            array('АБ ВГ', true, 'АБ ВГ'),
            array('АбвГ ДЕ', true, 'АБВГ ДЕ'),
        );
    }
}
