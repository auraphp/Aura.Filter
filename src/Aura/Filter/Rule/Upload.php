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
     * @param string|array $file_ext An array of allowed filename extensions
     * (without dots) for the file name.  If empty, all extensions are allowed.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($file_ext = null)
    {
        $value = $this->getValue();

        // has to be an array
        if (! is_array($value)) {
            $this->message = 'FILTER_UPLOAD_ERR_ARRAY';
            return false;
        }

        // presorted list of expected keys
        $expect = array('error', 'name', 'size', 'tmp_name', 'type');

        // sort the list of actual keys
        $actual = array_keys($value);
        sort($actual);

        // make sure the expected and actual keys match up
        if ($expect != $actual) {
            $this->message = 'FILTER_UPLOAD_ERR_ARRAY_KEYS';
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
        if (! is_uploaded_file($value['tmp_name'])) {
            // nefarious happenings are afoot.
            $this->message = 'FILTER_UPLOAD_ERR_IS_UPLOADED_FILE';
            return false;
        }

        // check file extension?
        if ($file_ext) {

            // find the file name extension, minus the dot
            $ext = substr(strrchr($value['name'], '.'), 1);

            // force to lower-case for comparisons
            $ext = strtolower($ext);

            // check against the allowed extensions
            foreach ((array) $file_ext as $val) {
                // force to lower-case for comparisons
                $val = strtolower($val);
                if ($ext == $val) {
                    // it's an allowed extension
                    return true;
                }
            }

            // didn't find the extension in the allowed list
            $this->message = 'FILTER_UPLOAD_ERR_FILE_EXT';
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

        // has to be an array
        if (! is_array($value)) {
            $this->setValue(null);
            return true;
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
            $this->setValue(null);
            return true;
        }

        // if all the non-error values are empty, still null
        $empty = empty($value['name']) &&
                 empty($value['size']) &&
                 empty($value['tmp_name']) &&
                 empty($value['type']);

        if ($empty) {
            $this->setValue(null);
            return true;
        }

        // everything looks ok; some keys may have been removed.
        $this->setValue($value);
        return true;
    }
}

