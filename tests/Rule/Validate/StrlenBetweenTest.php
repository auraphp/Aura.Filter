<?php
namespace Aura\Filter\Rule\Validate;

class StrlenBetweenTest extends AbstractValidateTest
{
    protected $min = 4;

    protected $max = 6;

    protected function getArgs()
    {
        return array($this->min, $this->max);
    }

    public static function providerIs()
    {
        return array(
            array('abcd'),
            array('efghi'),
            array('jklmno'),
            array('абвг'),
            array('ефхев'),
            array('вдгзас'),
        );
    }

    public static function providerIsNot()
    {
        return array(
            array(array()),
            array('abc'),
            array('defghij'),
            array('абв'),
            array('абвддгг'),
        );
    }
}
