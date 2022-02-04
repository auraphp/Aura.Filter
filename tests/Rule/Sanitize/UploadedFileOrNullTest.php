<?php
namespace Aura\Filter\Rule\Sanitize;

use GuzzleHttp\Psr7\UploadedFile as Psr7File;

class UploadedFileOrNullTest extends AbstractSanitizeTest
{

    public function providerTo()
    {
        $file = new Psr7File('file', 2048, UPLOAD_ERR_OK, 'foo.bar', 'foo/bar');
        $noFile = new Psr7File('file', 0,  UPLOAD_ERR_NO_FILE);

        return array(
            array( $file, true, $file ),
            array( $noFile, true, null ),
            array( 'not', true, null ),
        );
    }
}
