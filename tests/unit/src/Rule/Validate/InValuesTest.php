<?php
namespace Aura\Filter\Rule\Validate;

class InValuesTest extends AbstractValidateTest
{
    protected $opts = array(
        0      => 'val0',
        1      => 'val1',
        'key0' => 'val3',
        'key1' => 'val4',
        'key2' => 'val5',
    );

    public function getArgs()
    {
        return array($this->opts);
    }

    public function providerIs()
    {
        return array(
            array('val0'),
            array('val1'),
            array('val3'),
            array('val4'),
            array('val5'),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(3),
            array(4),
            array('a'),
            array('b'),
            array('c'),
        );
    }
}
