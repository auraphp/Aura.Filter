<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Validate;

use Psr\Http\Message\UploadedFileInterface;

/**
 *
 * Validates that the value is a PSR7 UploadedFile with various properties
 *
 * @package Aura.Filter
 *
 */
class UploadedFile
{

    const REQUIRED       = 'required';
    const FILE_EXTENSION = 'fileExtension';
    const FILE_MEDIA     = 'fileMedia';
    const SIZE_MAX       = 'sizeMax';
    const SIZE_MIN       = 'sizeMin';

    protected $rules = array(
        self::REQUIRED,
        self::FILE_EXTENSION,
        self::FILE_MEDIA,
        self::SIZE_MAX,
        self::SIZE_MIN,
    );


    /**
     *
     * Validates the presenece and properites if a PSR7 FileUploadInterface
     *
     * valid options:
     *  - required      : bool require file is uploaded
     *  - fileExtension : array or strign of acceptable file extensions
     *  - fileMedia     : array or strign of acceptable file media types
     *  - sizeMax       : bytes or human readable string
     *  - sizeMin       : bytes or human readable string
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, array $options)
    {
        $value = $subject->$field;

        if (! $this->isRequired($options)
            && ! $this->fileWasUploaded($value)
        ) {
            return true;
        }

        foreach ($this->rules as $rule) {
            if (array_key_exists($rule, $options)) {
                if (! $this->$rule($value, $options[$rule])) {
                    return false;
                }
            }
        }
        // looks like we're ok!
        return true;
    }

    /**
     * If the file required to be uploaded?
     *
     * @param array $options the options passed tot the filter
     *
     * @return bool
     *
     * @access protected
     */
    protected function isRequired(array $options)
    {
        return (
            isset($options[self::REQUIRED])
            && $options[self::REQUIRED] == true
        );
    }

    /**
     * Is value an instance of a PSR7 UploadedFileInterface?
     *
     * @param mixed $value value being validated
     *
     * @return bool
     *
     * @access protected
     */
    protected function isUploadedFile($value)
    {
        return ($value instanceof UploadedFileInterface);
    }

    /**
     * Was a file uploaded ?
     *
     * @param mixed $value value being validated
     *
     * @return bool
     *
     * @access protected
     */
    protected function fileWasUploaded($value)
    {
        if (! $this->isUploadedFile($value)) {
            return false;
        }

        if ($value->getError() !==  UPLOAD_ERR_OK) {
            return false;
        }

        return true;
    }

    /**
     * Required Rule Test
     *
     * @param mixed $value  value being validated
     * @param bool  $option if the file is required
     *
     * @return bool
     *
     * @access protected
     */
    protected function required($value, $option)
    {
        if ($option === false) {
            return true;
        }

        return $this->fileWasUploaded($value);
    }

    /**
     * FileExtension Rule Test
     *
     * @param mixed        $value value being validated
     * @param string|array $exts  array or string of valid extensions
     *
     * @return bool
     *
     * @access protected
     */
    protected function fileExtension($value, $exts)
    {
        $filename = $value->getClientFilename();
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, array_map('strtolower', (array) $exts));
    }

    /**
     * FileMedia Rule Test
     *
     * @param mixed        $value  value being validated
     * @param string|array $medias array or string of valid media types
     *
     * @return bool
     *
     * @access protected
     */
    protected function fileMedia($value, $medias)
    {
        $media = $value->getClientMediaType();
        return in_array($media, (array) $medias);
    }

    /**
     * SizeMax Rule Test
     *
     * @param mixed $value value being validated
     * @param mixed $max   maximum file size
     *
     * @return bool
     *
     * @access protected
     */
    protected function sizeMax($value, $max)
    {
        $size = $value->getSize();
        return $size < $this->parseHumanSize($max);
    }

    /**
     * SizeMin
     *
     * @param mixed $value value being validated
     * @param mixed $min   minimum file size
     *
     * @return mixed
     *
     * @access protected
     */
    protected function sizeMin($value, $min)
    {
        $size = $value->getSize();
        return $size > $this->parseHumanSize($min);
    }

    /**
     * Parse human readable size to bytes
     *
     * @param mixed $size size indicator
     *
     * @return double|int
     *
     * @access public
     */
    public function parseHumanSize($size)
    {
        $number = substr($size, 0, -2);
        switch(strtoupper(substr($size, -2))){
        case "KB":
            return $number * 1024;
        case "MB":
            return $number * pow(1024, 2);
        case "GB":
            return $number * pow(1024, 3);
        case "TB":
            return $number * pow(1024, 4);
        case "PB":
            return $number * pow(1024, 5);
        default:
            return $size;
        }
    }
}
