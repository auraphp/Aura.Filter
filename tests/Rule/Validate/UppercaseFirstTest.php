<?php

namespace Aura\Filter\Rule\Validate;

class UppercaseFirstTest extends AbstractValidateTest
{
    public static function providerIs()
    {
        return array(
            array('Ab Cd'),
            array('EFGH'),
            array('АБ ВВ'),
            array('Фг ег'),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(array()),
            array('aBCD'),
            array('ef GH'),
            array('аб ВВ'),
            array('фГ ЕГ'),
        );
    }
}

