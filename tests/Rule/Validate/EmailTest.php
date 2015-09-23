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
                $provide[(string) $test['id']] = array((string) $test->address);
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
                $provide[(string) $test['id']] = array((string) $test->address);
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
}
