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

    protected function getClass()
    {
        $class = parent::getClass();
        $class = str_replace('Upload', 'MockUpload', $class);
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
        ];
    }

    public function providerFix()
    {
        $fixed = [
            'error'     => UPLOAD_ERR_OK,
            'name'      => 'file.jpg',
            'size'      => '1024',
            'tmp_name'  => '/tmp/asdfghjkl.jpg',
            'type'      => 'image/jpeg',
        ];

        return [
            [[], false, []], // can't fix
            [$this->good_upload, true, $fixed],
        ];
    }

    // public function testRuleIs_notUploadedFile()
    // {
    //     list($data, $field) = $this->getPrep($this->good_upload);
    //     $rule = $this->newRule($data, $field);
    //     $rule->is_uploaded_file = false;
    //     $this->assertFalse($rule->is());
    // }
}
