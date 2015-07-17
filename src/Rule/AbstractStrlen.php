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
    protected function strlen($str)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str);
        }
        return strlen($str);
    }

    protected function substr($str, $start, $length = null)
    {
        if (function_exists('mb_substr')) {
            return mb_substr($str, $start, $length);
        }
        return substr($str, $start, $length);
    }
}
