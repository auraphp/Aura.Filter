<?php
/**
 *
 * This file is part of the Aura Project for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Locator;

use Aura\Filter\Rule\Sanitize;

/**
 *
 * A ServiceLocator implementation for loading and retaining Sanitize rule objects.
 *
 * @package Aura.Filter
 *
 */
class SanitizeLocator extends Locator
{
    /**
     *
     * Initialize the $factories property for the first time.
     *
     * @param array $factories An array of key-value pairs where the key is the
     * rule name and the value is a callable that returns a rule object.
     *
     * @return null
     *
     */
    protected function initFactories(array $factories)
    {
        $this->factories = array(
            'alnum'                 => function () { return new Sanitize\Alnum(); },
            'alpha'                 => function () { return new Sanitize\Alpha(); },
            'between'               => function () { return new Sanitize\Between(); },
            'bool'                  => function () { return new Sanitize\Boolean(); },
            'callback'              => function () { return new Sanitize\Callback(); },
            'dateTime'              => function () { return new Sanitize\DateTime(); },
            'field'                 => function () { return new Sanitize\Field(); },
            'float'                 => function () { return new Sanitize\Double(); },
            'int'                   => function () { return new Sanitize\Integer(); },
            'isbn'                  => function () { return new Sanitize\Isbn(); },
            'lowercase'             => function () { return new Sanitize\Lowercase(); },
            'lowercaseFirst'        => function () { return new Sanitize\LowercaseFirst(); },
            'max'                   => function () { return new Sanitize\Max(); },
            'min'                   => function () { return new Sanitize\Min(); },
            'now'                   => function () { return new Sanitize\Now(); },
            'regex'                 => function () { return new Sanitize\Regex(); },
            'remove'                => function () { return new Sanitize\Remove(); },
            'string'                => function () { return new Sanitize\Str(); },
            'strlen'                => function () { return new Sanitize\Strlen(); },
            'strlenBetween'         => function () { return new Sanitize\StrlenBetween(); },
            'strlenMax'             => function () { return new Sanitize\StrlenMax(); },
            'strlenMin'             => function () { return new Sanitize\StrlenMin(); },
            'titlecase'             => function () { return new Sanitize\Titlecase(); },
            'trim'                  => function () { return new Sanitize\Trim(); },
            'uppercase'             => function () { return new Sanitize\Uppercase(); },
            'uppercaseFirst'        => function () { return new Sanitize\UppercaseFirst(); },
            'uuid'                  => function () { return new Sanitize\Uuid(); },
            'uuidHexonly'           => function () { return new Sanitize\UuidHexonly(); },
            'value'                 => function () { return new Sanitize\Value(); },
            'word'                  => function () { return new Sanitize\Word(); },
        );
        parent::initFactories($factories);
    }
}
