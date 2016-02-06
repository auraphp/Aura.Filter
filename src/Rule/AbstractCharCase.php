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
 * Abstract rule for character case filters; supports the `mbstring`
 * extension.
 *
 * @package Aura.Filter
 *
 */
abstract class AbstractCharCase
{
    /**
     *
     * Is the `mbstring` extension loaded?
     *
     * @return bool
     *
     */
    protected function mbstring()
    {
        return extension_loaded('mbstring');
    }

    /**
     *
     * Proxy to `mb_convert_case()` when available; fall back to
     * `utf8_decode()` and `strtolower()` otherwise.
     *
     * @param string $str String to convert case.
     *
     * @return string
     *
     */
    protected function strtolower($str)
    {
        if ($this->mbstring()) {
            return mb_convert_case($str, MB_CASE_LOWER, 'UTF-8');
        }

        return strtolower(utf8_decode($str));
    }

    /**
     *
     * Proxy to `mb_convert_case()` when available; fall back to
     * `utf8_decode()` and `strtoupper()` otherwise.
     *
     * @param string $str String to convert case.
     *
     * @return string
     *
     */
    protected function strtoupper($str)
    {
        if ($this->mbstring()) {
            return mb_convert_case($str, MB_CASE_UPPER, 'UTF-8');
        }

        return strtoupper(utf8_decode($str));
    }

    /**
     *
     * Proxy to `mb_convert_case()` when available; fall back to
     * `utf8_decode()` and `ucwords()` otherwise.
     *
     * @param string $str String to convert case.
     *
     * @return int
     *
     */
    protected function ucwords($str)
    {
        if ($this->mbstring()) {
            return mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
        }

        return ucwords(utf8_decode($str));
    }
}
