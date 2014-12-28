<?php
namespace Aura\Filter\Rule\Sanitize;

class UploadTest extends AbstractSanitizeTest
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

    public function providerTo()
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
            ['not an array', false, 'not an array'], // can't fix
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
