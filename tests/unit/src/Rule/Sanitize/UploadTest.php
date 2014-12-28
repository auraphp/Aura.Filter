<?php
namespace Aura\Filter\Rule\Sanitize;

class UploadTest extends AbstractSanitizeTest
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

    public function providerTo()
    {
        $fixed = array(
            'error'     => UPLOAD_ERR_OK,
            'name'      => 'file.jpg',
            'size'      => '1024',
            'tmp_name'  => '/tmp/asdfghjkl.jpg',
            'type'      => 'image/jpeg',
        );

        return array(
            array(array(), false, array()), // can't fix
            array($this->good_upload, true, $fixed),
            array('not an array', false, 'not an array'), // can't fix
        );
    }

    // public function testRuleIs_notUploadedFile()
    // {
    //     list($data, $field) = $this->getPrep($this->good_upload);
    //     $rule = $this->newRule($data, $field);
    //     $rule->is_uploaded_file = false;
    //     $this->assertFalse($rule->is());
    // }
}
