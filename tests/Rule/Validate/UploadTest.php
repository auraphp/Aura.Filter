<?php
namespace Aura\Filter\Rule\Validate;

class UploadTest extends AbstractValidateTest
{
    protected $good_upload = array(
        'error'     => UPLOAD_ERR_OK,
        'name'      => 'file.jpg',
        'size'      => '1024',
        'tmp_name'  => '/tmp/asdfghjkl.jpg',
        'type'      => 'image/jpeg',
        'extra_key' => 'extra',
    );

    protected $bad_upload_1 = array(
        'error'     => UPLOAD_ERR_PARTIAL,
        'name'      => 'file.jpg',
        'size'      => '1024',
        'tmp_name'  => '/tmp/asdfghjkl.jpg',
        'type'      => 'image/jpeg',
        'extra_key' => 'extra',
    );

    protected $bad_upload_2 = array(
        'error'     => 96,
        'name'      => 'file.jpg',
        'size'      => '1024',
        'tmp_name'  => '/tmp/asdfghjkl.jpg',
        'type'      => 'image/jpeg',
        'extra_key' => 'extra',
    );

    // missing key
    protected $bad_upload_3 = array(
        'error'     => 96,
        'name'      => 'file.jpg',
        'tmp_name'  => '/tmp/asdfghjkl.jpg',
        'type'      => 'image/jpeg',
    );

    protected function getClass()
    {
        $class = parent::getClass();
        $class = str_replace('Upload', 'FakeUpload', $class);
        return $class;
    }

    public static function providerIs(): array
    {
        return array(
            array(array(
                'error'     => UPLOAD_ERR_OK,
                'name'      => 'file.jpg',
                'size'      => '1024',
                'tmp_name'  => '/tmp/asdfghjkl.jpg',
                'type'      => 'image/jpeg',
                'extra_key' => 'extra',
            )),
        );
    }

    public static function providerIsNot(): array
    {
        return array(
            array(null),
            array(array(                            // bad_upload_1: partial
                'error'     => UPLOAD_ERR_PARTIAL,
                'name'      => 'file.jpg',
                'size'      => '1024',
                'tmp_name'  => '/tmp/asdfghjkl.jpg',
                'type'      => 'image/jpeg',
                'extra_key' => 'extra',
            )),
            array(array(                            // bad_upload_2: unknown error code
                'error'     => 96,
                'name'      => 'file.jpg',
                'size'      => '1024',
                'tmp_name'  => '/tmp/asdfghjkl.jpg',
                'type'      => 'image/jpeg',
                'extra_key' => 'extra',
            )),
            array(array(                            // bad_upload_3: missing key
                'error'     => 96,
                'name'      => 'file.jpg',
                'tmp_name'  => '/tmp/asdfghjkl.jpg',
                'type'      => 'image/jpeg',
            )),
        );
    }

    public function testIs_notUploadedFile()
    {
        $class = $this->getClass();
        $rule = new $class();
        $rule->is_uploaded_file = false;
        $subject = (object) array('foo' => $this->good_upload);
        $this->assertFalse($rule->__invoke($subject, 'foo'));
    }
}
