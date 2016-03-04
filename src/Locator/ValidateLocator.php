<?php
/**
 *
 * This file is part of the Aura Project for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Locator;

use Aura\Filter\Rule\Validate;

/**
 *
 * A ServiceLocator implementation for loading and retaining Validate rule objects.
 *
 * @package Aura.Filter
 *
 */
class ValidateLocator extends Locator
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
            'alnum'                 => function () { return new Validate\Alnum(); },
            'alpha'                 => function () { return new Validate\Alpha(); },
            'between'               => function () { return new Validate\Between(); },
            'bool'                  => function () { return new Validate\Boolean(); },
            'callback'              => function () { return new Validate\Callback(); },
            'creditCard'            => function () { return new Validate\CreditCard(); },
            'dateTime'              => function () { return new Validate\DateTime(); },
            'email'                 => function () { return new Validate\Email(); },
            'equalToField'          => function () { return new Validate\EqualToField(); },
            'equalToValue'          => function () { return new Validate\EqualToValue(); },
            'float'                 => function () { return new Validate\Double(); },
            'inKeys'                => function () { return new Validate\InKeys(); },
            'inValues'              => function () { return new Validate\InValues(); },
            'int'                   => function () { return new Validate\Integer(); },
            'ip'                    => function () { return new Validate\Ip(); },
            'isbn'                  => function () { return new Validate\Isbn(); },
            'locale'                => function () { return new Validate\Locale(); },
            'lowercase'             => function () { return new Validate\Lowercase(); },
            'lowercaseFirst'        => function () { return new Validate\LowercaseFirst(); },
            'max'                   => function () { return new Validate\Max(); },
            'min'                   => function () { return new Validate\Min(); },
            'regex'                 => function () { return new Validate\Regex(); },
            'strictEqualToField'    => function () { return new Validate\StrictEqualToField(); },
            'strictEqualToValue'    => function () { return new Validate\StrictEqualToValue(); },
            'string'                => function () { return new Validate\Str(); },
            'strlen'                => function () { return new Validate\Strlen(); },
            'strlenBetween'         => function () { return new Validate\StrlenBetween(); },
            'strlenMax'             => function () { return new Validate\StrlenMax(); },
            'strlenMin'             => function () { return new Validate\StrlenMin(); },
            'titlecase'             => function () { return new Validate\Titlecase(); },
            'trim'                  => function () { return new Validate\Trim(); },
            'upload'                => function () { return new Validate\Upload(); },
            'uppercase'             => function () { return new Validate\Uppercase(); },
            'uppercaseFirst'        => function () { return new Validate\UppercaseFirst(); },
            'url'                   => function () { return new Validate\Url(); },
            'uuid'                  => function () { return new Validate\Uuid(); },
            'uuidHexonly'           => function () { return new Validate\UuidHexonly(); },
            'word'                  => function () { return new Validate\Word(); },
        );
        parent::initFactories($factories);
    }
}
