<?php
namespace Aura\Filter\Rule\Validate;

class EqualToValueTest extends AbstractValidateTest
{
    protected $other_value = '1';

    protected function getArgs()
    {
        $args = parent::getArgs();
        $args[] = $this->other_value;
        return $args;
    }

    public function providerIs()
    {
        return [
            [1],
            ['1'],
            [true],
        ];
    }

    public function providerIsNot()
    {
        return [
            [0],
            ['2'],
            [false],
        ];
    }
}
