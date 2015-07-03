<?php
/**
 *
 * This file is part of the Aura Project for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Locator;

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
    protected function initFactories(array $factories)
    {
        $this->factories = array(
            'alnum'                 => function () { return new Validate\Alnum(); },
            'alpha'                 => function () { return new Validate\Alpha(); },
            'between'               => function () { return new Validate\Between(); },
            'blank'                 => function () { return new Validate\Blank(); },
            'bool'                  => function () { return new Validate\Boolean(); },
            'callback'              => function () { return new Validate\Callback(); },
            'creditCard'            => function () { return new Validate\CreditCard(); },
            'dateTime'              => function () { return new Validate\DateTime(); },
            'email'                 => function () { return new Validate\Email(); },
            'equalToField'          => function () { return new Validate\EqualToField(); },
            'equalToValue'          => function () { return new Validate\EqualToValue(); },
            'float'                 => function () { return new Validate\Double(); },
            'inKeys'                => function () { return new Validate\InKeys(); },
            'int'                   => function () { return new Validate\Integer(); },
            'inValues'              => function () { return new Validate\InValues(); },
            'ip'                    => function () { return new Validate\Ip(); },
            'ipv4'                  => function () { return new Validate\Ipv4(); },
            'ipv6'                  => function () { return new Validate\Ipv6(); },
            'isbn'                  => function () { return new Validate\Isbn(); },
            'locale'                => function () { return new Validate\Locale(); },
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
            'trim'                  => function () { return new Validate\Trim(); },
            'upload'                => function () { return new Validate\Upload(); },
            'url'                   => function () { return new Validate\Url(); },
            'uuid'                  => function () { return new Validate\Uuid(); },
            'uuidHexonly'           => function () { return new Validate\UuidHexonly(); },
            'word'                  => function () { return new Validate\Word(); },
        );
        parent::initFactories($factories);
    }
}
