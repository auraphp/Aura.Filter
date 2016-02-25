<?php
namespace Aura\Filter\Rule\Validate;

class ExtantTest extends \PHPUnit_Framework_TestCase
{
    public function testIs()
    {
        $extant = new Extant();
        $subject = (object) [
            'foo' => 'foo',
            'bar' => null,
            'baz' => false,
            'dib' => 0,
            'zim' => ''
        ];
        $this->assertTrue($extant($subject, 'foo'));
        $this->assertTrue($extant($subject, 'bar'));
        $this->assertTrue($extant($subject, 'baz'));
        $this->assertTrue($extant($subject, 'dib'));
        $this->assertTrue($extant($subject, 'zim'));
    }

    public function testIsNot()
    {
        $extant = new Extant();
        $subject = (object) [];
        $this->assertFalse($extant($subject, 'foo'));
    }
}
