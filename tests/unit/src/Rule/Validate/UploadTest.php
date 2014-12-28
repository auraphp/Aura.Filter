<?php
namespace Aura\Filter\Rule\Validate;

class UploadTest extends AbstractValidateTest
{
    protected $good_upload = [
        'error'     => UPLOAD_ERR_OK,
        'name'      => 'file.jpg',
        'size'      => '1024',
        'tmp_name'  => '/tmp/asdfghjkl.jpg',
        'type'      => 'image/jpeg',
        'extra_key' => 'extra',
    ];

    protected $bad_upload_1 = [
        'error'     => UPLOAD_ERR_PARTIAL,
        'name'      => 'file.jpg',
        'size'      => '1024',
        'tmp_name'  => '/tmp/asdfghjkl.jpg',
        'type'      => 'image/jpeg',
        'extra_key' => 'extra',
    ];

    protected $bad_upload_2 = [
        'error'     => 96,
        'name'      => 'file.jpg',
        'size'      => '1024',
        'tmp_name'  => '/tmp/asdfghjkl.jpg',
        'type'      => 'image/jpeg',
        'extra_key' => 'extra',
    ];

    // missing key
    protected $bad_upload_3 = [
        'error'     => 96,
        'name'      => 'file.jpg',
        'tmp_name'  => '/tmp/asdfghjkl.jpg',
        'type'      => 'image/jpeg',
    ];

    protected function getClass()
    {
        $class = parent::getClass();
        $class = str_replace('Upload', 'FakeUpload', $class);
        return $class;
    }

    public function providerIs()
    {
        return [
            [$this->good_upload],
        ];
    }

    public function providerIsNot()
    {
        return [
            [null], // not an array,
            [$this->bad_upload_1],
            [$this->bad_upload_2],
            [$this->bad_upload_3],
        ];
    }

    public function testIs_notUploadedFile()
    {
        $class = $this->getClass();
        $rule = new $class();
        $rule->is_uploaded_file = false;
        $object = (object) array('field' => $this->good_upload);
        $this->assertFalse($rule->__invoke($object, 'field'));
    }
}
