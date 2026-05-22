<?php

namespace Aura\Filter\Rule\Validate;

class TitlecaseTest extends AbstractValidateTest
{
    public static function providerIs()
    {
        return array(
            array('Ab Cd'),
            array('Efgh'),
            array('Аб Вв'),
            array('Фг Ег'),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(array()),
            array('aBcd'),
            array('Ef gH'),
            array('АБ ВВ'),
            array('ФГ ег'),
        );
    }
}

