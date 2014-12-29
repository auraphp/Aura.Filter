<?php
namespace Aura\Filter\Rule\Validate;

class UrlTest extends AbstractValidateTest
{
    public function providerIs()
    {
        return array(
            array("http://example.com"),
            array("https://example.com/path/to/file.php"),
            array("ftp://example.com/path/to/file.php/info"),
            array("news://example.com/path/to/file.php/info?foo=bar&baz=dib#zim"),
            array("gopher://example.com/?foo=bar&baz=dib#zim"),
            array("mms://user:pass@site.info/path/to/file.php/info?foo=bar&baz=dib#zim"),
        );
    }

    public function providerIsNot()
    {
        return array(
            array(array()),
            array(''),
            array(' '),
            array('example.com'),
            array('http://'),
            array("http://example.com\r/index.html"),
            array("http://example.com\n/index.html"),
            array("http://example.com\t/index.html"),
        );
    }
}
