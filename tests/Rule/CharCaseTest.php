<?php
namespace Aura\Filter\Rule;

class CharCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testLower()
    {
        $fake = new FakeCharCase();
        $this->assertSame(
            'abcdef',
            $fake->strtolower('ABCDEF')
        );
    }

    public function testUpper()
    {
        $fake = new FakeCharCase();
        $this->assertSame(
            'ABCDEF',
            $fake->strtoupper('abcdef')
        );
    }

    public function testUcWords()
    {
        $fake = new FakeCharCase();
        $this->assertSame(
            'Abc Def',
            $fake->ucwords('abc def')
        );
    }

}
