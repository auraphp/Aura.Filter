<?php

namespace Aura\Filter\Rule\Validate;

class UppercaseTest extends AbstractValidateTest
{
    public static function providerIs()
    {
        return array(
            array('AB CD'),
            array('EFGH'),
            array('АБ ВВ'),
            array('ФГ ЕГ'),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(array()),
            array('aBcD'),
            array('Ef gH'),
            array('Аб ВВ'),
            array('ФГ ег'),
        );
    }
}

