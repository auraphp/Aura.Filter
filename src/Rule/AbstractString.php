<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule;

use Aura\Filter\Exception;

/**
 *
 * Abstract rule for string-length filters; supports the `mbstring` extension.
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractString
{
    protected function mbstring()
    {
        return extension_loaded('mbstring');
    }

    protected function iconv()
    {
        return extension_loaded('iconv');
    }

    protected function pregValidate($pattern, $utf8pattern, $subject)
    {
        if (! is_scalar($subject)) {
            return false;
        }

        if ($this->iconv() || $this->mbstring()) {
            return preg_match($utf8pattern, $subject, $matches);
        }

        return preg_match($pattern, $subject);
    }

    protected function pregSanitize($pattern, $utf8pattern, $subject)
    {
        if ($this->iconv() || $this->mbstring()) {
            return preg_replace($utf8pattern, '', $subject);
        }

        return preg_replace($pattern, '', $subject);
    }

    /**
     *
     * Proxy to `mb_strlen()` when the `mbstring` extension is loaded,
     * otherwise to `strlen()`.
     *
     * @param string $str Returns the length of this string.
     *
     * @return int
     *
     */
    protected function strlen($str)
    {
        if ($this->iconv()) {
            return iconv_strlen($str, 'UTF-8');
        }

        if ($this->mbstring()) {
            return mb_strlen($str, 'UTF-8');
        }

        return strlen($str);
    }

    /**
     *
     * Proxy to `mb_substr()` when the `mbstring` extension is loaded,
     * otherwise to `substr()`.
     *
     * @param string $str The string to work with.
     *
     * @param int $start Start at this position.
     *
     * @param int $length End after this many characters.
     *
     * @return string
     *
     */
    protected function substr($str, $start, $length = null)
    {
        if ($this->iconv()) {
            return iconv_substr($str, $start, $length, 'UTF-8');
        }

        if ($this->mbstring()) {
            return mb_substr($str, $start, $length, 'UTF-8');
        }

        return substr($str, $start, $length);
    }

    /**
     *
     * Proxy to `$this->mbsubstr()` when the `mbstring` extension is loaded,
     * otherwise to `substr()`.
     *
     * @param string $input The input string.
     *
     * @param int $length If the value of length is negative, less than, or
     * equal to the length of the input string, no padding takes place.
     *
     * @param string $pad_str The pad_str may be truncated if the required
     * number of padding characters can't be evenly divided by the pad_string's
     * length.
     *
     * @param int $type Optional argument pad_type can be STR_PAD_RIGHT,
     * STR_PAD_LEFT, or STR_PAD_BOTH. If pad_type is not specified it is
     * assumed to be STR_PAD_RIGHT.
     *
     * @return string
     *
     */
    protected function strpad($input, $length, $pad_str = " ", $type = STR_PAD_RIGHT)
    {
        if ($this->mbstring()) {
            return $this->mbStrPad($input, $length, $pad_str, $type, 'UTF-8');
        }

        if ($this->iconv()) {
            return $this->iconvStrPad($input, $length, $pad_str, $type, 'UTF-8');
        }

        return str_pad($input, $length, $pad_str, $type);
    }

    /**
     *
     * Userland implmementation of multibyte `str_pad()`.
     *
     * @param string $input The input string.
     *
     * @param int $length If the value of length is negative, less than, or
     * equal to the length of the input string, no padding takes place.
     *
     * @param string $pad_str The pad_str may be truncated if the required
     * number of padding characters can't be evenly divided by the pad_string's
     * length.
     *
     * @param int $type Optional argument pad_type can be STR_PAD_RIGHT,
     * STR_PAD_LEFT, or STR_PAD_BOTH. If pad_type is not specified it is
     * assumed to be STR_PAD_RIGHT.
     *
     * @param string $encoding The encoding of *both* $input string *and*
     * $pad_str.
     *
     * @return string
     *
     */
    protected function mbStrPad(
        $input,
        $length,
        $pad_str = ' ',
        $type = STR_PAD_RIGHT
    ) {
        $encoding = 'UTF-8';

        $input_len = mb_strlen($input, $encoding);
        if ($length <= $input_len) {
            return $input;
        }

        $pad_str_len = mb_strlen($pad_str, $encoding);
        $pad_len = $length - $input_len;

        if ($type == STR_PAD_RIGHT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            $input .= str_repeat($pad_str, $repeat_times);
            return mb_substr($input, 0, $length, $encoding);
        }

        if ($type == STR_PAD_LEFT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            $prefix = str_repeat($pad_str, $repeat_times);
            return mb_substr($prefix, 0, floor($pad_len), $encoding) . $input;
        }

        if ($type == STR_PAD_BOTH) {
            $pad_len /= 2;
            $pad_amount_left = floor($pad_len);
            $pad_amount_right = ceil($pad_len);
            $repeat_times_left = ceil($pad_amount_left / $pad_str_len);
            $repeat_times_right = ceil($pad_amount_right / $pad_str_len);

            $prefix = str_repeat($pad_str, $repeat_times_left);
            $padding_left = mb_substr($prefix, 0, $pad_amount_left, $encoding);

            $suffix = str_repeat($pad_str, $repeat_times_right);
            $padding_right = mb_substr($suffix, 0, $pad_amount_right, $encoding);

            return $padding_left . $input . $padding_right;
        }
    }

    /**
     *
     * Userland implmementation of multibyte `str_pad()`.
     *
     * @param string $input The input string.
     *
     * @param int $length If the value of length is negative, less than, or
     * equal to the length of the input string, no padding takes place.
     *
     * @param string $pad_str The pad_str may be truncated if the required
     * number of padding characters can't be evenly divided by the pad_string's
     * length.
     *
     * @param int $type Optional argument pad_type can be STR_PAD_RIGHT,
     * STR_PAD_LEFT, or STR_PAD_BOTH. If pad_type is not specified it is
     * assumed to be STR_PAD_RIGHT.
     *
     * @param string $encoding The encoding of *both* $input string *and*
     * $pad_str.
     *
     * @return string
     *
     */
    protected function iconvStrPad(
        $input,
        $length,
        $pad_str = ' ',
        $type = STR_PAD_RIGHT
    ) {
        $encoding = 'UTF-8';

        $input_len = iconv_strlen($input, $encoding);
        if ($length <= $input_len) {
            return $input;
        }

        $pad_str_len = iconv_strlen($pad_str, $encoding);
        $pad_len = $length - $input_len;

        if ($type == STR_PAD_RIGHT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            $input .= str_repeat($pad_str, $repeat_times);
            return iconv_substr($input, 0, $length, $encoding);
        }

        if ($type == STR_PAD_LEFT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            $prefix = str_repeat($pad_str, $repeat_times);
            return iconv_substr($prefix, 0, floor($pad_len), $encoding) . $input;
        }

        if ($type == STR_PAD_BOTH) {
            $pad_len /= 2;
            $pad_amount_left = floor($pad_len);
            $pad_amount_right = ceil($pad_len);
            $repeat_times_left = ceil($pad_amount_left / $pad_str_len);
            $repeat_times_right = ceil($pad_amount_right / $pad_str_len);

            $prefix = str_repeat($pad_str, $repeat_times_left);
            $padding_left = iconv_substr($prefix, 0, $pad_amount_left, $encoding);

            $suffix = str_repeat($pad_str, $repeat_times_right);
            $padding_right = iconv_substr($suffix, 0, $pad_amount_right, $encoding);

            return $padding_left . $input . $padding_right;
        }
    }
}
