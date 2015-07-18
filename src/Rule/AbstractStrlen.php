<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule;

/**
 *
 * Abstract rule for string-length filters; supports the `mbstring` extension.
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractStrlen
{
    protected function detectEncoding($str)
    {
        return mb_detect_encoding($str, mb_detect_order(), true);
    }

    protected function convertToUtf8($str, $from)
    {
        return mb_convert_encoding($str, 'UTF-8', $from);
    }

    protected function convertFromUtf8($str, $to)
    {
        return mb_convert_encoding($str, $to, 'UTF-8');
    }

    /**
     *
     * Proxy to `mb_strlen()` when it exists, otherwise to `strlen()`.
     *
     * @param string $str Returns the length of this string.
     *
     * @param string $encoding The encoding used, UTF-8 by default
     *
     * @return int
     *
     */
    protected function strlen($str)
    {
        if (extension_loaded('mbstring')) {
            $encoding = $this->detectEncoding($str);
            $str = $this->convertToUtf8($str, $encoding);
            return mb_strlen($str, 'UTF-8');
        }
        return strlen($str);
    }

    /**
     *
     * Proxy to `mb_substr()` when it exists, otherwise to `substr()`.
     *
     * @param string $str The string to work with.
     *
     * @param int $start Start at this position.
     *
     * @param int $length End after this many characters.
     *
     * @param string $encoding The encoding used, UTF-8 by default
     *
     * @return string
     *
     */
    protected function substr($str, $start, $length = null)
    {
        if (extension_loaded('mbstring')) {
            $encoding = $this->detectEncoding($str);
            $str = $this->convertToUtf8($str, $encoding);
            $result = mb_substr($str, $start, $length, 'UTF-8');
            return $this->convertFromUtf8($result, $encoding);
        }
        return substr($str, $start, $length);
    }

    /**
     *
     * Userland implmementation of multibyte `str_pad()` when multibyte exists;
     * otherwise proxies to `str_pad()`.
     *
     * @param string $input The string to work with.
     *
     * @param int $length How much symbols do we want it to be
     *
     * @param string $pad_str What are we going to pad it with
     *
     * @param int $type Where should it be padded - left,right or both
     *
     * @return string
     *
     */
    protected function strpad($input, $length, $pad_str = " ", $type = STR_PAD_RIGHT)
    {
        if (extension_loaded('mbstring')) {
            $input_encoding = $this->detectEncoding($input);
            $input = $this->convertToUtf8($input, $input_encoding);
            $pad_str_encoding = $this->detectEncoding($pad_str);
            $pad_str = $this->convertToUtf8($pad_str, $pad_str_encoding);
            $result = $this->mbstrpad($input, $length, $pad_str, $type, 'UTF-8');
            return $this->convertFromUtf8($result, $input_encoding);
        }

        return str_pad($input, $length, $pad_str, $type);
    }

    protected function mbstrpad($input, $length, $pad_str = " ", $type = STR_PAD_RIGHT, $encoding = 'UTF-8')
    {
        $input_len = mb_strlen($input, $encoding);
        if ($length <= $input_len) {
            return $input;
        }

        $pad_str_len = mb_strlen($pad_str, $encoding);
        $pad_len = $length - $input_len;
        if ($type == STR_PAD_RIGHT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            return mb_substr($input . str_repeat($pad_str, $repeat_times), 0, $length, $encoding);
        }

        if ($type == STR_PAD_LEFT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            return mb_substr(str_repeat($pad_str, $repeat_times), 0, floor($pad_len), $encoding) . $input;
        }

        if ($type == STR_PAD_BOTH) {
            $pad_len /= 2;
            $pad_amount_left = floor($pad_len);
            $pad_amount_right = ceil($pad_len);
            $repeat_times_left = ceil($pad_amount_left / $pad_str_len);
            $repeat_times_right = ceil($pad_amount_right / $pad_str_len);
            $padding_left = mb_substr(str_repeat($pad_str, $repeat_times_left), 0, $pad_amount_left, $encoding);
            $padding_right = mb_substr(str_repeat($pad_str, $repeat_times_right), 0, $pad_amount_right, $encoding);
            return $padding_left . $input . $padding_right;
        }
    }
}