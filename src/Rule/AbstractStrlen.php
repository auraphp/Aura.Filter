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
    protected function strlen($str) {
        if (function_exists('mb_strlen')) {
            $encoding = mb_internal_encoding();
            return mb_strlen($str, $encoding);
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
    protected function substr($str, $start, $length = null) {
        if (function_exists('mb_substr')) {
            $encoding = mb_internal_encoding();
            return mb_substr($str, $start, $length, $encoding);
        }
        return substr($str, $start, $length);
    }

    /**
     *
     * We need another function for str_padding
     *
     * @param string $input The string to work with.
     *
     * @param int $length How much symbols do we want it to be
     *
     * @param string $pad_str What are we going to pad it with
     *
     * @param int $type Where should it be padded - left,right or both
     *
     * @param string $encoding The encoding used, UTF-8 by default
     *
     * @return string
     *
     */
    protected function strpad($input, $length, $pad_str = " ", $type = STR_PAD_RIGHT)
    {
        $encoding = mb_internal_encoding();
        $input_len = $this->strlen($input,$encoding);
        if ($length <= $input_len) {
            return $input;
        }
        $pad_str_len = $this->strlen($pad_str,$encoding);
        $pad_len = $length - $input_len;
        if ($type == STR_PAD_RIGHT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            return $this->substr($input . str_repeat($pad_str, $repeat_times), 0, $length,$encoding);
        }
        if ($type == STR_PAD_LEFT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            return $this->substr(str_repeat($pad_str, $repeat_times), 0, floor($pad_len),$encoding) . $input;
        }
        if ($type == STR_PAD_BOTH) {
            $pad_len /= 2;
            $pad_amount_left = floor($pad_len);
            $pad_amount_right = ceil($pad_len);
            $repeat_times_left = ceil($pad_amount_left / $pad_str_len);
            $repeat_times_right = ceil($pad_amount_right / $pad_str_len);
            $padding_left = $this->substr(str_repeat($pad_str, $repeat_times_left), 0, $pad_amount_left,$encoding);
            $padding_right = $this->substr(str_repeat($pad_str, $repeat_times_right), 0, $pad_amount_right,$encoding);
            return $padding_left . $input . $padding_right;
        }
    }
}