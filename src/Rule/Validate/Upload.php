<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates that the value is an array of file-upload information, and
 * if a file is referred to, that is actually an uploaded file.
 *
 * @package Aura.Filter
 *
 */
class Upload
{
    /**
     *
     * Validates that the value is an array of file-upload information, and
     * if a file is referred to, that is actually an uploaded file.
     *
     * The required keys are 'error', 'name', 'size', 'tmp_name', 'type'. More
     * or fewer or different keys than this will return a "malformed" error.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;

        $well_formed = $this->preCheck($value);
        if (! $well_formed) {
            return false;
        }

        // was the upload explicitly ok?
        $err = $value['error'];
        if ($err != UPLOAD_ERR_OK) {
            return false;
        }

        // is it actually an uploaded file?
        if (! $this->isUploadedFile($value['tmp_name'])) {
            return false;
        }

        // looks like we're ok!
        return true;
    }

    /**
     *
     * Check that the file-upload array is well-formed.
     *
     * @param array $value The file-upload array.
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
            return false;
        }

        // looks ok
        return true;
    }

    /**
     *
     * Check whether the file was uploaded via HTTP POST.
     *
     * @param string $file The file to check.
     *
     * @return bool True if the file was uploaded via HTTP POST, false if not.
     *
     */
    protected function isUploadedFile($file)
    {
        return is_uploaded_file($file);
    }
}
