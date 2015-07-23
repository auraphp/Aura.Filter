<?php
namespace Aura\Filter\Rule\Sanitize;

class StrTest extends AbstractSanitizeTest
{
    protected $find = ' ';
    protected $repl = '@';

    protected function getArgs()
    {
        return array($this->find, $this->repl);
    }

    public function providerTo()
    {
        return array(
            array('abc 123 ,./', true, 'abc@123@,./'),
            array('abc 123 ,./ абв', true, 'abc@123@,./@абв'),
            array(12345, true, '12345'),
        );
    }
}
