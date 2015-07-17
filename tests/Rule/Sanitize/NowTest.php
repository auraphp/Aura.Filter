<?php
namespace Aura\Filter\Rule\Sanitize;

class NowTest extends AbstractSanitizeTest
{
    protected $format = 'Y-m-d';

    protected function getArgs()
    {
        $args = parent::getArgs();
        $args[] = $this->format;
        return $args;
    }

    public function providerTo()
    {
        $now = date('Y-m-d');
        return array(
            array(0,         true, $now),
            array(1,         true, $now),
            array('1',       true, $now),
            array(true,      true, $now),
            array(false,     true, $now),
        );
    }
}
