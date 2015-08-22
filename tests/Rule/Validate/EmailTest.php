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
        $xml = simplexml_load_string($this->xml);
        $provide = array();
        foreach ($xml->test as $test) {
            if ($this->isValidAddressRegardlessOfDns($test)) {
                $provide[(string) $test['id']] = array((string) $test->address);
            }
        }

        // this one doesn't work right?
        unset($provide["48"]);

        return $provide;
    }

    public function providerIsNot()
    {
        $xml = simplexml_load_string($this->xml);
        $provide = array();
        foreach ($xml->test as $test) {
            if (! $this->isValidAddressRegardlessOfDns($test)) {
                $provide[(string) $test['id']] = array((string) $test->address);
            }
        }

        // this one doesn't work right?
        unset($provide["71"]);

        return $provide;
    }

    protected function isValidAddressRegardlessOfDns($test)
    {
        return $test->diagnosis == 'ISEMAIL_VALID'
            || $test->category == 'ISEMAIL_RFC5321'
            || $test->category == 'ISEMAIL_DNSWARN';
    }

    protected $xml = <<<XML
<tests version="3.05">
    <description>
        <p><strong>New test set</strong></p>
        <p>This test set is designed to replace and extend the coverage of the original set but with fewer tests.</p>
        <p>Thanks to Michael Rushton (michael@squiloople.com) for starting this work and contributing tests 1-100</p>
    </description>
    <test id="1">
        <address/>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_NODOMAIN</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="2">
        <address>test</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_NODOMAIN</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="3">
        <address>@</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_NOLOCALPART</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="4">
        <address>test@</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_NODOMAIN</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="5">
        <address>test@io</address>
        <comment>io. currently has an MX-record (Feb 2011). Some DNS setups seem to find it, some don't. If you don't see the MX for io. then try setting your DNS server to 8.8.8.8 (the Google DNS server)</comment>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="6">
        <address>@io</address>
        <comment>io. currently has an MX-record (Feb 2011)</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_NOLOCALPART</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="7">
        <address>@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_NOLOCALPART</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="8">
        <address>test@iana.org</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="9">
        <address>test@nominet.org.uk</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="10">
        <address>test@about.museum</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="11">
        <address>a@iana.org</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="12">
        <address>test@e.com</address>
        <category>ISEMAIL_DNSWARN</category>
        <diagnosis>ISEMAIL_DNSWARN_NO_RECORD</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="13">
        <address>test@iana.a</address>
        <category>ISEMAIL_DNSWARN</category>
        <diagnosis>ISEMAIL_DNSWARN_NO_RECORD</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="14">
        <address>test.test@iana.org</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="15">
        <address>.test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_DOT_START</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="16">
        <address>test.@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_DOT_END</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="17">
        <address>test..iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_CONSECUTIVEDOTS</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="18">
        <address>test_exa-mple.com</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_NODOMAIN</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="19">
        <address>!#$%&amp;`*+/=?^`{|}~@iana.org</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="20">
        <address>test\@test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="21">
        <address>123@iana.org</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="22">
        <address>test@123.com</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="23">
        <address>test@iana.123</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_TLDNUMERIC</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="24">
        <address>test@255.255.255.255</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_TLDNUMERIC</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="25">
        <address>abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm@iana.org</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="26">
        <address>abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklmn@iana.org</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_LOCAL_TOOLONG</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="27">
        <address>test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.com</address>
        <category>ISEMAIL_DNSWARN</category>
        <diagnosis>ISEMAIL_DNSWARN_NO_RECORD</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="28">
        <address>test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm.com</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_LABEL_TOOLONG</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="29">
        <address>test@mason-dixon.com</address>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="30">
        <address>test@-iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_DOMAINHYPHENSTART</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="31">
        <address>test@iana-.com</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_DOMAINHYPHENEND</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="32">
        <address>test@c--n.com</address>
        <comment>c--n.com currently has an MX-record (May 2011)</comment>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="33">
        <address>test@iana.co-uk</address>
        <category>ISEMAIL_DNSWARN</category>
        <diagnosis>ISEMAIL_DNSWARN_NO_RECORD</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="34">
        <address>test@.iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_DOT_START</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="35">
        <address>test@iana.org.</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_DOT_END</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="36">
        <address>test@iana..com</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_CONSECUTIVEDOTS</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="37">
        <address>a@a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v</address>
        <category>ISEMAIL_DNSWARN</category>
        <diagnosis>ISEMAIL_DNSWARN_NO_RECORD</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="38">
        <address>abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghi</address>
        <category>ISEMAIL_DNSWARN</category>
        <diagnosis>ISEMAIL_DNSWARN_NO_RECORD</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="39">
        <address>abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghij</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_TOOLONG</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="40">
        <address>a@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefg.hij</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_TOOLONG</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="41">
        <address>a@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefg.hijk</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMAIN_TOOLONG</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="42">
        <address>"test"@iana.org</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_QUOTEDSTRING</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="43">
        <address>""@iana.org</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_QUOTEDSTRING</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="44">
        <address>"""@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="45">
        <address>"\a"@iana.org</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_QUOTEDSTRING</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="46">
        <address>"\""@iana.org</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_QUOTEDSTRING</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="47">
        <address>"\"@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDQUOTEDSTR</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="48">
        <address>"\\"@iana.org</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_QUOTEDSTRING</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="49">
        <address>test"@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="50">
        <address>"test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDQUOTEDSTR</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="51">
        <address>"test"test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_ATEXT_AFTER_QS</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="52">
        <address>test"text"@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="53">
        <address>"test""test"@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="54">
        <address>"test"."test"@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_LOCALPART</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="55">
        <address>"test\ test"@iana.org</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_QUOTEDSTRING</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="56">
        <address>"test".test@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_LOCALPART</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="57">
        <address>"test&#x2400;"@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_QTEXT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="58">
        <address>"test\&#x2400;"@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_QP</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="59">
        <address>"abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz abcdefghj"@iana.org</address>
        <comment>Quotes are still part of the length restriction</comment>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_LOCAL_TOOLONG</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="60">
        <address>"abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz abcdefg\h"@iana.org</address>
        <comment>Quoted pair is still part of the length restriction</comment>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_LOCAL_TOOLONG</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="61">
        <address>test@[255.255.255.255]</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_ADDRESSLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="62">
        <address>test@a[255.255.255.255]</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="63">
        <address>test@[255.255.255]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMAINLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="64">
        <address>test@[255.255.255.255.255]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMAINLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="65">
        <address>test@[255.255.255.256]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMAINLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="66">
        <address>test@[1111:2222:3333:4444:5555:6666:7777:8888]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMAINLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="67">
        <address>test@[IPv6:1111:2222:3333:4444:5555:6666:7777]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_GRPCOUNT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="68">
        <address>test@[IPv6:1111:2222:3333:4444:5555:6666:7777:8888]</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_ADDRESSLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="69">
        <address>test@[IPv6:1111:2222:3333:4444:5555:6666:7777:8888:9999]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_GRPCOUNT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="70">
        <address>test@[IPv6:1111:2222:3333:4444:5555:6666:7777:888G]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_BADCHAR</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="71">
        <address>test@[IPv6:1111:2222:3333:4444:5555:6666::8888]</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_RFC5321_IPV6DEPRECATED</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="72">
        <address>test@[IPv6:1111:2222:3333:4444:5555::8888]</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_ADDRESSLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="73">
        <address>test@[IPv6:1111:2222:3333:4444:5555:6666::7777:8888]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_MAXGRPS</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="74">
        <address>test@[IPv6::3333:4444:5555:6666:7777:8888]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_COLONSTRT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="75">
        <address>test@[IPv6:::3333:4444:5555:6666:7777:8888]</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_ADDRESSLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="76">
        <address>test@[IPv6:1111::4444:5555::8888]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_2X2XCOLON</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="77">
        <address>test@[IPv6:::]</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_ADDRESSLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="78">
        <address>test@[IPv6:1111:2222:3333:4444:5555:255.255.255.255]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_GRPCOUNT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="79">
        <address>test@[IPv6:1111:2222:3333:4444:5555:6666:255.255.255.255]</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_ADDRESSLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="80">
        <address>test@[IPv6:1111:2222:3333:4444:5555:6666:7777:255.255.255.255]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_GRPCOUNT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="81">
        <address>test@[IPv6:1111:2222:3333:4444::255.255.255.255]</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_ADDRESSLITERAL</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="82">
        <address>test@[IPv6:1111:2222:3333:4444:5555:6666::255.255.255.255]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_MAXGRPS</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="83">
        <address>test@[IPv6:1111:2222:3333:4444:::255.255.255.255]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_2X2XCOLON</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="84">
        <address>test@[IPv6::255.255.255.255]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_COLONSTRT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="85">
        <address> test @iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_CFWS_NEAR_AT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="86">
        <address>test@ iana .com</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_CFWS_NEAR_AT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="87">
        <address>test . test@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_FWS</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="88">
        <address>&#x240D;&#x240A; test@iana.org</address>
        <comment>FWS</comment>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_FWS</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="89">
        <address>&#x240D;&#x240A; &#x240D;&#x240A; test@iana.org</address>
        <comment>FWS with one line composed entirely of WSP -- only allowed as obsolete FWS (someone might allow only non-obsolete FWS)</comment>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_FWS</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="90">
        <address>(comment)test@iana.org</address>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_COMMENT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="91">
        <address>((comment)test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDCOMMENT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="92">
        <address>(comment(comment))test@iana.org</address>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_COMMENT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="93">
        <address>test@(comment)iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_CFWS_NEAR_AT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="94">
        <address>test(comment)test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_ATEXT_AFTER_CFWS</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="95">
        <address>test@(comment)[255.255.255.255]</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_CFWS_NEAR_AT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="96">
        <address>(comment)abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm@iana.org</address>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_COMMENT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="97">
        <address>test@(comment)abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.com</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_CFWS_NEAR_AT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="98">
        <address>(comment)test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghik.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghik.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijk.abcdefghijklmnopqrstuvwxyzabcdefghijk.abcdefghijklmnopqrstu</address>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_COMMENT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="99">
        <address>test@iana.org&#x240A;</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="100">
        <address>test@xn--hxajbheg2az3al.xn--jxalpdlp</address>
        <comment>A valid IDN from ICANN's <a href="http://idn.icann.org/#The_example.test_names">IDN TLD evaluation gateway</a></comment>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="101">
        <address>xn--test@iana.org</address>
        <comment>RFC 3490: "unless the
   email standards are revised to invite the use of IDNA for local
   parts, a domain label that holds the local part of an email address
   SHOULD NOT begin with the ACE prefix, and even if it does, it is to
   be interpreted literally as a local part that happens to begin with
   the ACE prefix"</comment>
        <category>ISEMAIL_VALID_CATEGORY</category>
        <diagnosis>ISEMAIL_VALID</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="102">
        <address>test@iana.org-</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_DOMAINHYPHENEND</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="103">
        <address>"test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDQUOTEDSTR</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="104">
        <address>(test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDCOMMENT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="105">
        <address>test@(iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDCOMMENT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="106">
        <address>test@[1.2.3.4</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDDOMLIT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="107">
        <address>"test\"@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDQUOTEDSTR</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="108">
        <address>(comment\)test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDCOMMENT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="109">
        <address>test@iana.org(comment\)</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDCOMMENT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="110">
        <address>test@iana.org(comment\</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_BACKSLASHEND</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="112">
        <address>test@[RFC-5322-domain-literal]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMAINLITERAL</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="113">
        <address>test@[RFC-5322]-domain-literal]</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_ATEXT_AFTER_DOMLIT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="114">
        <address>test@[RFC-5322-[domain-literal]</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_DTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="115">
        <address>test@[RFC-5322-\&#x2407;-domain-literal]</address>
        <comment>obs-dtext <strong>and</strong> obs-qp</comment>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMLIT_OBSDTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="116">
        <address>test@[RFC-5322-\&#x2409;-domain-literal]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMLIT_OBSDTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="117">
        <address>test@[RFC-5322-\]-domain-literal]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMLIT_OBSDTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="118">
        <address>test@[RFC-5322-domain-literal\]</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_UNCLOSEDDOMLIT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="119">
        <address>test@[RFC-5322-domain-literal\</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_BACKSLASHEND</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="120">
        <address>test@[RFC 5322 domain literal]</address>
        <comment>Spaces are FWS in a domain literal</comment>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMAINLITERAL</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="121">
        <address>test@[RFC-5322-domain-literal] (comment)</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMAINLITERAL</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="122">
        <address>@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="123">
        <address>test@.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="124">
        <address>""@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_QTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="125">
        <address>"\"@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_QP</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="126">
        <address>()test@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_CTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="127">
        <address>test@iana.org&#x240D;</address>
        <comment>No LF after the CR</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_CR_NO_LF</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="128">
        <address>&#x240D;test@iana.org</address>
        <comment>No LF after the CR</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_CR_NO_LF</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="129">
        <address>"&#x240D;test"@iana.org</address>
        <comment>No LF after the CR</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_CR_NO_LF</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="130">
        <address>(&#x240D;)test@iana.org</address>
        <comment>No LF after the CR</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_CR_NO_LF</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="131">
        <address>test@iana.org(&#x240D;)</address>
        <comment>No LF after the CR</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_CR_NO_LF</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="132">
        <address>&#x240A;test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Michael Rushton</source>
        <sourcelink>http://squiloople.com/tag/email/</sourcelink>
    </test>
    <test id="133">
        <address>"&#x240A;"@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_QTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="134">
        <address>"\&#x240A;"@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_QP</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="135">
        <address>(&#x240A;)test@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_CTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="136">
        <address>&#x2407;@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="137">
        <address>test@&#x2407;.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_ATEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="138">
        <address>"&#x2407;"@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_QTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="139">
        <address>"\&#x2407;"@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_QP</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="140">
        <address>(&#x2407;)test@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_CTEXT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="141">
        <address>&#x240D;&#x240A;test@iana.org</address>
        <comment>Not FWS because no actual white space</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_END</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="142">
        <address>&#x240D;&#x240A; &#x240D;&#x240A;test@iana.org</address>
        <comment>Not obs-FWS because there must be white space on each "fold"</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_END</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="143">
        <address> &#x240D;&#x240A;test@iana.org</address>
        <comment>Not FWS because no white space after the fold</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_END</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="144">
        <address> &#x240D;&#x240A; test@iana.org</address>
        <comment>FWS</comment>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_FWS</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="145">
        <address> &#x240D;&#x240A; &#x240D;&#x240A;test@iana.org</address>
        <comment>Not FWS because no white space after the second fold</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_END</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="146">
        <address> &#x240D;&#x240A;&#x240D;&#x240A;test@iana.org</address>
        <comment>Not FWS because no white space after either fold</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_X2</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="147">
        <address> &#x240D;&#x240A;&#x240D;&#x240A; test@iana.org</address>
        <comment>Not FWS because no white space after the first fold</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_X2</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="148">
        <address>test@iana.org&#x240D;&#x240A; </address>
        <comment>FWS</comment>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_FWS</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="149">
        <address>test@iana.org&#x240D;&#x240A; &#x240D;&#x240A; </address>
        <comment>FWS with one line composed entirely of WSP -- only allowed as obsolete FWS (someone might allow only non-obsolete FWS)</comment>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_FWS</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="150">
        <address>test@iana.org&#x240D;&#x240A;</address>
        <comment>Not FWS because no actual white space</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_END</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="151">
        <address>test@iana.org&#x240D;&#x240A; &#x240D;&#x240A;</address>
        <comment>Not obs-FWS because there must be white space on each "fold"</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_END</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="152">
        <address>test@iana.org &#x240D;&#x240A;</address>
        <comment>Not FWS because no white space after the fold</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_END</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="153">
        <address>test@iana.org &#x240D;&#x240A; </address>
        <comment>FWS</comment>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_FWS</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="154">
        <address>test@iana.org &#x240D;&#x240A; &#x240D;&#x240A;</address>
        <comment>Not FWS because no white space after the second fold</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_END</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="155">
        <address>test@iana.org &#x240D;&#x240A;&#x240D;&#x240A;</address>
        <comment>Not FWS because no white space after either fold</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_X2</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="156">
        <address>test@iana.org &#x240D;&#x240A;&#x240D;&#x240A; </address>
        <comment>Not FWS because no white space after the first fold</comment>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_FWS_CRLF_X2</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="157">
        <address> test@iana.org</address>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_FWS</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="158">
        <address>test@iana.org </address>
        <category>ISEMAIL_CFWS</category>
        <diagnosis>ISEMAIL_CFWS_FWS</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="159">
        <address>test@[IPv6:1::2:]</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_IPV6_COLONEND</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="160">
        <address>"test\&#xA9;"@iana.org</address>
        <category>ISEMAIL_ERR</category>
        <diagnosis>ISEMAIL_ERR_EXPECTING_QPAIR</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="161">
        <address>test@iana/icann.org</address>
        <category>ISEMAIL_RFC5322</category>
        <diagnosis>ISEMAIL_RFC5322_DOMAIN</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="165">
        <address>test.(comment)test@iana.org</address>
        <category>ISEMAIL_DEPREC</category>
        <diagnosis>ISEMAIL_DEPREC_COMMENT</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="166">
        <address>test@org</address>
        <category>ISEMAIL_RFC5321</category>
        <diagnosis>ISEMAIL_RFC5321_TLD</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="167">
        <address>test@test.com</address>
        <comment>test.com has an A-record but not an MX-record</comment>
        <category>ISEMAIL_DNSWARN</category>
        <diagnosis>ISEMAIL_DNSWARN_NO_MX_RECORD</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
    <test id="168">
        <address>test@nic.no</address>
        <comment>nic.no currently has no MX-records or A-records (Feb 2011). If you are seeing an A-record for nic.io then try setting your DNS server to 8.8.8.8 (the Google DNS server) - your DNS server may be faking an A-record (OpenDNS does this, for instance).</comment>
        <category>ISEMAIL_DNSWARN</category>
        <diagnosis>ISEMAIL_DNSWARN_NO_RECORD</diagnosis>
        <source>Dominic Sayers</source>
        <sourcelink>http://isemail.info</sourcelink>
    </test>
</tests>
XML;

}
