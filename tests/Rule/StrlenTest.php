<?php
namespace Aura\Filter\Rule;

class StrlenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider fakeProvider
     */
    public function testStrlen($fake)
    {
        $this->assertSame(12, $fake->strlen('abcdefабвгде'));
    }

    /**
     * @dataProvider fakeProvider
     */
    public function testSubstr($fake)
    {
        $expect = 'defабв';
        $actual = $fake->substr('abcdefабвгде', 3, 6);
        $this->assertSame($expect, $actual);
    }

    /**
     * @dataProvider fakeProvider
     */
    public function testStrpad($fake)
    {
        $str = 'defабв';
        $pad = 'д';

        $expect = $str;
        $actual = $fake->strpad($str, 6, $pad);
        $this->assertSame($expect, $actual);

        $expect = 'defабвддддд';
        $actual = $fake->strpad($str, 11, $pad, STR_PAD_RIGHT);
        $this->assertSame($expect, $actual);

        $expect = 'дддддdefабв';
        $actual = $fake->strpad($str, 11, $pad, STR_PAD_LEFT);
        $this->assertSame($expect, $actual);

        $expect = 'ддdefабвддд';
        $actual = $fake->strpad($str, 11, $pad, STR_PAD_BOTH);
        $this->assertSame($expect, $actual);
    }

    public function testIconvStrlenMalformedUtf8()
    {
        $bad = "\xFF\xFE";
        $fake = new FakeStrlenIconv();
        $this->setExpectedException('Aura\Filter\Exception\MalformedUtf8');
        $fake->strlen($bad); 
    }
    
    public function testIconvSubstrMalformedUtf8()
    {
        $bad = "\xFF\xFE";
        $fake = new FakeStrlenIconv();
        $this->setExpectedException('Aura\Filter\Exception\MalformedUtf8');
        $fake->substr($bad,0,1); 
    }

    public function fakeProvider()
    {
        $fakes = array(
            array(new FakeStrlen()),
        );

        if (extension_loaded('mbstring')) {
            $fakes[] = array(new FakeStrlenMbstring());
        }

        if (extension_loaded('iconv')) {
            $fakes[] = array(new FakeStrlenIconv());
        }

        return $fakes;
    }
}
