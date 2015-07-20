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

    protected function pregValidate($pattern, $utf8pattern, $subject)
    {
        if (! is_scalar($subject)) {
            return false;
        }

        if (! $this->mbstring()) {
            return preg_match($pattern, $subject);
        }

        $encoding = $this->detectEncoding($subject);
        $subject = $this->convertToUtf8($subject, $encoding);
        return preg_match($utf8pattern, $subject, $matches);
    }

    protected function pregSanitize($pattern, $utf8pattern, $subject)
    {
        if (! $this->mbstring()) {
            return preg_replace($pattern, '', $subject);
        }

        $encoding = $this->detectEncoding($subject);
        $subject = $this->convertToUtf8($subject, $encoding);
        $subject = preg_replace($utf8pattern, '', $subject);
        return $this->convertFromUtf8($subject, $encoding);
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
        if (! $this->mbstring()) {
            return strlen($str);
        }

        $encoding = $this->detectEncoding($str);
        $str = $this->convertToUtf8($str, $encoding);
        return mb_strlen($str, 'UTF-8');
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
        if (! $this->mbstring()) {
            return substr($str, $start, $length);
        }

        $encoding = $this->detectEncoding($str);
        $str = $this->convertToUtf8($str, $encoding);
        $result = mb_substr($str, $start, $length, 'UTF-8');
        return $this->convertFromUtf8($result, $encoding);
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
        if (! $this->mbstring()) {
            return str_pad($input, $length, $pad_str, $type);
        }

        $input_encoding = $this->detectEncoding($input);
        $input = $this->convertToUtf8($input, $input_encoding);
        $pad_str_encoding = $this->detectEncoding($pad_str);
        $pad_str = $this->convertToUtf8($pad_str, $pad_str_encoding);
        $result = $this->mbstrpad($input, $length, $pad_str, $type, 'UTF-8');
        return $this->convertFromUtf8($result, $input_encoding);
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
    protected function mbstrpad(
        $input,
        $length,
        $pad_str = ' ',
        $type = STR_PAD_RIGHT,
        $encoding = 'UTF-8'
    ) {
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
     * Detects the string encoding.
     *
     * @param string $str The string.
     *
     * @return string
     *
     */
    protected function detectEncoding($str)
    {
        $encoding = mb_detect_encoding($str, mb_detect_order(), true);
        if ($encoding) {
            return $encoding;
        }

        throw new Exception\EncodingDetectionFailed();
    }

    /**
     *
     * Converts a string to UTF-8 from another encoding.
     *
     * @param string $str The string.
     *
     * @param string $from Convert from this encoding.
     *
     * @return string
     *
     */
    protected function convertToUtf8($str, $from)
    {
        return mb_convert_encoding($str, 'UTF-8', $from);
    }

    /**
     *
     * Converts a string from UTF-8 to another encoding.
     *
     * @param string $str The string.
     *
     * @param string $to Convert to this encoding.
     *
     * @return string
     *
     */
    protected function convertFromUtf8($str, $to)
    {
        return mb_convert_encoding($str, $to, 'UTF-8');
    }
}
