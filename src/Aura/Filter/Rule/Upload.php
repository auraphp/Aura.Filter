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

use StdClass;

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
     * Error message
     *
     * @var string
     */
    protected $message = 'FILTER_UPLOAD';

    /**
     * 
     * Upload error codes matched with locale string keys.
     * 
     * @var array
     * 
     */
    protected $error_message = [
        UPLOAD_ERR_INI_SIZE   => 'FILTER_UPLOAD_ERR_INI_SIZE',
        UPLOAD_ERR_FORM_SIZE  => 'FILTER_UPLOAD_ERR_FORM_SIZE',
        UPLOAD_ERR_PARTIAL    => 'FILTER_UPLOAD_ERR_PARTIAL',
        UPLOAD_ERR_NO_FILE    => 'FILTER_UPLOAD_ERR_NO_FILE',
        UPLOAD_ERR_NO_TMP_DIR => 'FILTER_UPLOAD_ERR_NO_TMP_DIR',
        UPLOAD_ERR_CANT_WRITE => 'FILTER_UPLOAD_ERR_CANT_WRITE',
        UPLOAD_ERR_EXTENSION  => 'FILTER_UPLOAD_ERR_EXTENSION', // **php** extension
    ];

    /**
     * 
     * prepare the rule for reuse
     * 
     * @param StdClass $data
     * 
     * @param string $field
     */
    public function prep(StdClass $data, $field)
    {
        parent::prep($data, $field);
        $this->message = 'FILTER_UPLOAD';
    }

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
    protected function validate()
    {
        $value = $this->getValue();

        $success = $this->preCheck($value);
        if (! $success) {
            return false;
        }

        // was the upload explicitly ok?
        if ($value['error'] != UPLOAD_ERR_OK) {

            // not explicitly ok, so find what the error was
            foreach ($this->error_message as $error => $message) {
                if ($value['error'] == $error) {
                    $this->message = $message;
                    return false;
                }
            }

            // some other error
            $this->message = 'FILTER_UPLOAD_ERR_UNKNOWN';
            return false;
        }

        // is it actually an uploaded file?
        if (! $this->isUploadedFile($value['tmp_name'])) {
            // nefarious happenings are afoot.
            $this->message = 'FILTER_UPLOAD_ERR_IS_UPLOADED_FILE';
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
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize()
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
     * check before the file is uploaded
     * 
     * @param string $value
     * 
     * @return boolean
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
            $this->message = 'FILTER_UPLOAD_ERR_ARRAY_KEYS';
            return false;
        }

        // looks ok
        return true;
    }

    /**
     * check whether the file was uploaded via HTTP POST
     * 
     * @param string $file
     * 
     * @return type
     * 
     */
    protected function isUploadedFile($file)
    {
        return is_uploaded_file($file);
    }
}
