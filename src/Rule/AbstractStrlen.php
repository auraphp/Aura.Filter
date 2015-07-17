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
     * @return int
     *
     */
    protected function strlen($str)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str);
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
     * @return string
     *
     */
    protected function substr($str, $start, $length = null)
    {
        if (function_exists('mb_substr')) {
            return mb_substr($str, $start, $length);
        }
        return substr($str, $start, $length);
    }
}
