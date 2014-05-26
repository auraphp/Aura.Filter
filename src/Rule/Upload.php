<?php
/**
 * 
 * This file is part of the Aura project for PHP.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRule;

/**
 * 
 * Sanitizes a file-upload information array.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Upload extends AbstractRule
{
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_UPLOAD',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_UPLOAD',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_UPLOAD',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_UPLOAD',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_UPLOAD',
        UPLOAD_ERR_INI_SIZE     => 'FILTER_RULE_ERR_UPLOAD_INI_SIZE',
        UPLOAD_ERR_FORM_SIZE    => 'FILTER_RULE_ERR_UPLOAD_FORM_SIZE',
        UPLOAD_ERR_PARTIAL      => 'FILTER_RULE_ERR_UPLOAD_PARTIAL',
        UPLOAD_ERR_NO_FILE      => 'FILTER_RULE_ERR_UPLOAD_NO_FILE',
        UPLOAD_ERR_NO_TMP_DIR   => 'FILTER_RULE_ERR_UPLOAD_NO_TMP_DIR',
        UPLOAD_ERR_CANT_WRITE   => 'FILTER_RULE_ERR_UPLOAD_CANT_WRITE',
        UPLOAD_ERR_EXTENSION    => 'FILTER_RULE_ERR_UPLOAD_EXTENSION', // **php** extension
        'err_unknown'           => 'FILTER_RULE_ERR_UPLOAD_UNKNOWN',
        'err_is_uploaded_file'  => 'FILTER_RULE_ERR_UPLOAD_IS_UPLOADED_FILE',
        'err_array_keys'        => 'FILTER_RULE_ERR_UPLOAD_ARRAY_KEYS',
    ];

    /**
     * 
     * Validates that the value is an array of file-upload information, and
     * if a file is referred to, that is actually an uploaded file.
     * 
     * The required keys are 'error', 'name', 'size', 'tmp_name', 'type'. More
     * or fewer or different keys than this will return a "malformed" error.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate()
    {
        $value = $this->getValue();

        $success = $this->preCheck($value);
        if (! $success) {
            return false;
        }

        // was the upload explicitly ok?
        $err = $value['error'];
        if ($err != UPLOAD_ERR_OK) {
            if (isset($this->message_map[$err])) {
                $this->setMessageKey($err);
            } else {
                $this->setMessageKey('err_unknown');
            }
            return false;
        }

        // is it actually an uploaded file?
        if (! $this->isUploadedFile($value['tmp_name'])) {
            // nefarious happenings are afoot.
            $this->setMessageKey('err_is_uploaded_file');
            return false;
        }

        // looks like we're ok!
        return true;
    }

    /**
     * 
     * Sanitizes a file-upload information array.  If no file has been 
     * uploaded, the information will be returned as null.
     * 
     * @return bool True if the value was sanitized, false if not.
     * 
     */
    public function sanitize()
    {
        $value = $this->getValue();

        // pre-check
        $success = $this->preCheck($value);
        if (! $success) {
            return false;
        }

        // everything looks ok; some keys may have been removed.
        $this->setValue($value);
        return true;
    }

    /**
     * 
     * Check before the file is uploaded
     * 
     * @param string $value
     * 
     * @return bool
     * 
     */
    protected function preCheck(&$value)
    {
        // has to be an array
        if (! is_array($value)) {
            return false;
        }

        // presorted list of expected keys
        $expect = array('error', 'name', 'size', 'tmp_name', 'type');

        // remove unexpected keys
        foreach ($value as $key => $val) {
            if (! in_array($key, $expect)) {
                unset($value[$key]);
            }
        }

        // sort the list of remaining actual keys
        $actual = array_keys($value);
        sort($actual);

        // make sure the expected and actual keys match up
        if ($expect != $actual) {
            $this->setMessageKey('err_array_keys');
            return false;
        }

        // looks ok
        return true;
    }

    /**
     * 
     * Check whether the file was uploaded via HTTP POST
     * 
     * @param string $file
     * 
     * @return bool True if the file was uploaded via HTTP POST, false if not.
     * 
     */
    protected function isUploadedFile($file)
    {
        return is_uploaded_file($file);
    }
}
