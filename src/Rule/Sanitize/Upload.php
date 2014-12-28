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
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Sanitizes a file-upload information array.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Upload
{
    /**
     *
     * Sanitizes a file-upload information array.  If no file has been
     * uploaded, the information will be returned as null.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($object, $field)
    {
        $value = $object->$field;

        // pre-check
        $success = $this->preCheck($value);
        if (! $success) {
            return false;
        }

        // everything looks ok; some keys may have been removed.
        $object->$field = $value;

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
            return false;
        }

        // looks ok
        return true;
    }
}
