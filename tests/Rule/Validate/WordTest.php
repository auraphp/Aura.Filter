<?php
namespace Aura\Filter\Rule\Validate;

class WordTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array('abc'),
            array('def'),
            array('ghi'),
            array('abc_def'),
            array('A1s_2Sd'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array(''),
            array('a!'),
            array('^b'),
            array('%'),
            array('ab-db cd-ef'),
        );
    }
}
