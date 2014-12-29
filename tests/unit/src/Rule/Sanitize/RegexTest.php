<?php
namespace Aura\Filter\Rule\Sanitize;

class RegexTest extends AbstractSanitizeTest
{
    protected $expr_sanitize = '/[^a-z]/';

    protected function getArgs()
    {
        return array($this->expr_sanitize, '@');
    }

    public function providerTo()
    {
        return array(
            array(array(), false, array()),
            array('abc 123 ,./', true, 'abc@@@@@@@@'),
        );
    }
}
