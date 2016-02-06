<?php
namespace Aura\Filter\Rule\Validate;

/**
 * Copyright © 2008-2011, Dominic Sayers
 * Test schema documentation Copyright © 2011, Daniel Marschall
 * All rights reserved.
 * @author  Dominic Sayers <dominic@sayers.cc>
 * @copyright   2008-2011 Dominic Sayers
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link    http://www.dominicsayers.com/isemail
 * @version 3.04.1 - Changed my link to http://isemail.info throughout
 */
class EmailTest extends AbstractValidateTest
{
    public function providerIs()
    {
        $xml = simplexml_load_file(__DIR__ . DIRECTORY_SEPARATOR . 'EmailTest.xml');
        $provide = array();
        foreach ($xml->test as $test) {
            if ($this->isValidAddressRegardlessOfDns($test)) {
                $this->appendToProvide($provide, $test);
            }
        }

        return $provide;
    }

    public function providerIsNot()
    {
        $xml = simplexml_load_file(__DIR__ . DIRECTORY_SEPARATOR . 'EmailTest.xml');
        $provide = array();
        foreach ($xml->test as $test) {
            if (! $this->isValidAddressRegardlessOfDns($test)) {
                $this->appendToProvide($provide, $test);
            }
        }
        return $provide;
    }

    protected function isValidAddressRegardlessOfDns($test)
    {
        return $test->diagnosis == 'ISEMAIL_VALID'
            || $test->diagnosis == 'ISEMAIL_RFC5321_IPV6DEPRECATED'
            || $test->category == 'ISEMAIL_RFC5321'
            || $test->category == 'ISEMAIL_DNSWARN';
    }

    protected function appendToProvide(&$provide, $test)
    {
        $provide[(string) $test['id']] = array(
            $this->convertSymbolsToControls((string) $test->address)
        );
    }

    /**
     * The XML test file uses text symbol strings to represent ASCII control
     * codes. This converts the text symbols to the actual control characters.
     */
    protected function convertSymbolsToControls($address)
    {
        // &#x2407; => BEL 7 ␇
        // &#x2409; => HT 9  ␉
        // &#x240A; => LF 10 ␊
        // &#x240D; => CR 13 ␍
        return str_replace(
            ['␇', '␉', '␊', '␍'],
            [chr(7), chr(9), chr(10), chr(13)],
            $address
        );
    }

    public function testIDN()
    {
        if (!extension_loaded('intl')) {
            $this->markTestSkipped('The Intl extension is not available');
        }

        // add IDN addresses here so we don't pollute the original XML file.
        // addresses courtesy of David Grudl.
        $idn = array(
            'test@háčkyčárky.cz',
            'test@example.укр'
        );

        foreach ($idn as $value) {
            $this->assertTrue($this->invoke($value));
        }
    }
}
