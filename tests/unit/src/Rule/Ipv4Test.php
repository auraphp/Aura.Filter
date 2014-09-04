<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRuleTest;

class Ipv4Test extends AbstractRuleTest
{
    protected $expect_message = 'FILTER_RULE_FAILURE_IS_IPV4';

    public function providerIs()
    {
        return [
            ['141.225.185.101'],
            ['255.0.0.0'],
            ['0.255.0.0'],
            ['0.0.255.0'],
            ['0.0.0.255'],
            ['127.0.0.1'],
        ];
    }

    public function providerIsNot()
    {
        return [
            [' '],
            [''],
            ['127.0.0.1234'],
            ['127.0.0.0.1'],
            ['256.0.0.0'],
            ['0.256.0.0'],
            ['0.0.256.0'],
            ['0.0.0.256'],
            ['1.'],
            ['1.2.'],
            ['1.2.3.'],
            ['1.2.3.4.'],
            ['a.b.c.d'],
        ];
    }

    public function providerFix()
    {
        return [
            [12345, false, 12345], // can't fix
        ];
    }
}
