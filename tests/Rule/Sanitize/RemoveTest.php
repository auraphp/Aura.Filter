<?php
namespace Aura\Filter\Rule\Sanitize;

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class RemoveTest extends TestCase
{
    public function testTo()
    {
        $subject = (object) array(
            'foo' => 'bar',
            'baz' => 'dib',
        );

        $rule = new Remove;
        $rule->__invoke($subject, 'foo');

        $expect = (object) array(
            'baz' => 'dib',
        );

        $this->assertEquals($expect, $subject);
    }
}
