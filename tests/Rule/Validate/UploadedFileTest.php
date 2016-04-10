<?php
namespace Aura\Filter\Rule\Validate;

use Zend\Diactoros\UploadedFile as Psr7File;

class UploadedFileTest extends AbstractValidateTest
{
    public function providerIs()
    {
        $file = new Psr7File('file', 2048, UPLOAD_ERR_OK, 'foo.bar', 'foo/bar');
        $noFile = new Psr7File('file', 0,  UPLOAD_ERR_NO_FILE);

        $params = array(
            array( // uploaded, required
                $file,
                array(UploadedFile::REQUIRED => true)
            ),
            array( // uploaded, not required
                $file,
                array(UploadedFile::REQUIRED => false)
            ),
            array( // not uploaded, not required
                $noFile,
                array(UploadedFile::REQUIRED => false)
            ),

            // Extension
            array( // not uploaded, not required, bad ext
                $noFile,
                array(
                    UploadedFile::REQUIRED => false,
                    UploadedFile::FILE_EXTENSION => 'baz'
                )
            ),
            array( // uploaded, good ext
                $file,
                array(UploadedFile::FILE_EXTENSION => 'bar')
            ),
            array( // uploaded, good ext
                $file,
                array(UploadedFile::FILE_EXTENSION => ['baz', 'bar'])
            ),

            // Media
            array( // not uploaded, not required, bad media
                $noFile,
                array(
                    UploadedFile::REQUIRED => false,
                    UploadedFile::FILE_MEDIA => 'bing/baz'
                )
            ),
            array( // uploaded, good media
                $file,
                array(UploadedFile::FILE_MEDIA => 'foo/bar')
            ),
            array( // uploaded, good media
                $file,
                array(UploadedFile::FILE_MEDIA => ['bing/baz', 'foo/bar'])
            ),

            // Size Max
            array( // not uploaded, not required, bad size
                $noFile,
                array(
                    UploadedFile::REQUIRED => false,
                    UploadedFile::SIZE_MAX => 1
                )
            ),
            array( // uploaded, good size
                $file,
                array(UploadedFile::SIZE_MAX => 4096)
            ),
            array( // uploaded, good size
                $file,
                array(UploadedFile::SIZE_MAX => '3KB')
            ),

            // Size Min
            array( // not uploaded, not required, bad size
                $noFile,
                array(
                    UploadedFile::REQUIRED => false,
                    UploadedFile::SIZE_MIN => 999999
                )
            ),
            array( // uploaded, good size
                $file,
                array(UploadedFile::SIZE_MIN => 1024)
            ),
            array( // uploaded, good size
                $file,
                array(UploadedFile::SIZE_MIN => '1KB')
            ),
        );

        return $params;
    }

    public function providerIsNot()
    {
        $file = new Psr7File('file', 2048, UPLOAD_ERR_OK, 'foo.bar', 'foo/bar');
        $noFile = new Psr7File('file', 0,  UPLOAD_ERR_NO_FILE);

        return array(
            array( // not uploaded, required
                $noFile,
                array(UploadedFile::REQUIRED => true)
            ),
            array( // not interface, required
                null,
                array(UploadedFile::REQUIRED => true)
            ),

            // Extension
            array( // uploaded, bad ext
                $file,
                array(UploadedFile::FILE_EXTENSION => 'baz')
            ),
            array( // uploaded, bad exts
                $file,
                array(UploadedFile::FILE_EXTENSION => ['baz', 'bing'])
            ),

            // Media
            array( // uploaded, bad media
                $file,
                array(UploadedFile::FILE_MEDIA => 'bing/baz')
            ),
            array( // uploaded, bad medias
                $file,
                array(UploadedFile::FILE_MEDIA => ['bing/baz', 'boom/bang'])
            ),

            // Size Max
            array( // uploaded, bad size
                $file,
                array(UploadedFile::SIZE_MAX => 1024)
            ),
            array( // uploaded, bad size
                $file,
                array(UploadedFile::SIZE_MAX => '1KB')
            ),

            // Size Min
            array( // uploaded, bad size
                $file,
                array(UploadedFile::SIZE_MIN => 9999999)
            ),
            array( // uploaded, bad size
                $file,
                array(UploadedFile::SIZE_MIN => '1MB')
            ),
        );
    }

    protected function invoke($value, $args = null)
    {
        $subject = $this->getSubject($value);
        $field = 'foo';
        $rule = $this->newRule();
        return call_user_func($rule, $subject, $field, $args);
    }

    /**
     * @dataProvider providerIs
     */
    public function testIs($value, $args = null)
    {
        $this->assertTrue($this->invoke($value, $args));
    }

    /**
     * @dataProvider providerIsNot
     */
    public function testIsNot($value, $args = null)
    {
        $this->assertFalse($this->invoke($value, $args));
    }

    public function testParseHumanSize()
    {
        $units = [
            '1KB' => 1024,
            '1MB' => pow(1024, 2),
            '1GB' => pow(1024, 3),
            '1TB' => pow(1024, 4),
            '1PB' => pow(1024, 5)
        ];

        $rule = $this->newRule();

        foreach ($units as $human => $machine) {
            $this->assertEquals(
                $machine,
                $rule->parseHumanSize($human)
            );
        }
    }
}
