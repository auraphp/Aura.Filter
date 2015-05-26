<?php
namespace Aura\Filter\Rule\Sanitize;

class CallbackTest extends AbstractSanitizeTest
{
    protected function getArgs()
    {
        return array(function ($subject, $field) {
            $value = $subject->$field;
            $subject->$field = (bool) $value;
            return true;
        });
    }

    public function providerTo()
    {
        return array(
            array(0, true, false),
            array(1, true, true),
        );
    }
}
