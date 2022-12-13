<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule;

use Aura\Filter\Exception\MalformedUtf8;
use Aura\Filter\Exception;

use function Aura\Filter\utf8_to_iso8859_1;

/**
 *
 * Abstract rule for string-length filters; supports the `iconv` and `mbstring`
 * extensions.
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractStrlen
{
    /**
     *
     * Is the `mbstring` extension loaded?
     *
     * @return bool
     *
     */
    protected function mbstring(): bool
    {
        return extension_loaded('mbstring');
    }

    /**
     *
     * Is the `iconv` extension loaded?
     *
     * @return bool
     *
     */
    protected function iconv(): bool
    {
        return extension_loaded('iconv');
    }

    /**
     *
     * Proxy to `iconv_strlen()` or `mb_strlen()` when available; fall back to
     * `utf8_decode()` and `strlen()` otherwise.
     *
     * @param string $str Return the number of characters in this string.
     *
     * @return int
     *
     */
    protected function strlen(string $str): int
    {
        if ($this->iconv()) {
            return $this->strlenIconv($str);
        }

        if ($this->mbstring()) {
            return mb_strlen($str, 'UTF-8');
        }

        return strlen(utf8_to_iso8859_1($str));
    }

     /**
     *
     * Wrapper for `iconv_substr()` to throw an exception on malformed UTF-8.
     *
     * @param string $str The string to work with.
     *
     * @param int $start Start at this position.
     *
     * @param int $length End after this many characters.
     *
     * @return string
     *
     * @throws Exception\MalformedUtf8
     *
     */
    protected function substrIconv(string $str,int $start,int $length)
    {
        $level = error_reporting(0);
        $substr = iconv_substr($str,$start,$length, 'UTF-8');
        error_reporting($level);

        if ($substr !== false) {
            return $substr;
        }

        throw new MalformedUtf8();
    }

    /**
     *
     * Wrapper for `iconv_strlen()` to throw an exception on malformed UTF-8.
     *
     * @param string $str Return the number of characters in this string.
     *
     * @return int
     *
     * @throws Exception\MalformedUtf8
     *
     */
    protected function strlenIconv(string $str)
    {
        $level = error_reporting(0);
        $strlen = iconv_strlen($str, 'UTF-8');
        error_reporting($level);

        if ($strlen !== false) {
            return $strlen;
        }

        throw new MalformedUtf8();
    }

    /**
     *
     * Proxy to `iconv_substr()` or `mb_substr()` when the `mbstring` available;
     * polyfill via `preg_split()` and `array_slice()` otherwise.
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
    protected function substr(string $str, int $start, int $length = null): string
    {
        if ($this->iconv()) {
            return $this->substrIconv($str, $start, $length);
        }

        if ($this->mbstring()) {
            return mb_substr($str, $start, $length, 'UTF-8');
        }

        $split = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
        return implode('', array_slice($split, $start, $length));
    }

    /**
     *
     * Userland UTF-8-aware implementation of `str_pad()`.
     *
     * @param string $input The input string.
     *
     * @param int $pad_length If the value of pad_length is negative, less than,
     * or equal to the length of the input string, no padding takes place.
     *
     * @param string $pad_str Pad with this string. The pad_string may be
     * truncated if the required number of padding characters can't be evenly
     * divided by the pad_string's length.
     *
     * @param int $pad_type Optional argument pad_type can be STR_PAD_RIGHT,
     * STR_PAD_LEFT, or STR_PAD_BOTH. If pad_type is not specified it is
     * assumed to be STR_PAD_RIGHT.
     *
     * @return string
     *
     */
    protected function strpad(string $input, int $pad_length, string $pad_str = ' ', int $pad_type = STR_PAD_RIGHT)
    {
        $input_len = $this->strlen($input);
        if ($pad_length <= $input_len) {
            return $input;
        }

        $pad_str_len = $this->strlen($pad_str);
        $pad_len = $pad_length - $input_len;

        if ($pad_type == STR_PAD_LEFT) {
            $repeat_times = (int) ceil($pad_len / $pad_str_len);
            $prefix = str_repeat($pad_str, $repeat_times);
            return $this->substr($prefix, 0, (int) floor($pad_len)) . $input;
        }

        if ($pad_type == STR_PAD_BOTH) {
            $pad_len /= 2;
            $pad_amount_left = (int) floor($pad_len);
            $pad_amount_right = (int) ceil($pad_len);
            $repeat_times_left = (int) ceil($pad_amount_left / $pad_str_len);
            $repeat_times_right = (int) ceil($pad_amount_right / $pad_str_len);

            $prefix = str_repeat($pad_str, $repeat_times_left);
            $padding_left = $this->substr($prefix, 0, $pad_amount_left);

            $suffix = str_repeat($pad_str, $repeat_times_right);
            $padding_right = $this->substr($suffix, 0, $pad_amount_right);

            return $padding_left . $input . $padding_right;
        }

        // STR_PAD_RIGHT
        $repeat_times = (int) ceil($pad_len / $pad_str_len);
        $input .= str_repeat($pad_str, $repeat_times);
        return $this->substr($input, 0, $pad_length);
    }
}
