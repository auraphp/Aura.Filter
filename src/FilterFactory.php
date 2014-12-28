<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter;

use Aura\Filter\Rule\RuleLocator;
use Aura\Filter\Rule\Sanitize;
use Aura\Filter\Rule\Validate;
use Aura\Filter\Spec\SanitizeSpec;
use Aura\Filter\Spec\ValidateSpec;

/**
 *
 * Factory to create Filter objects.
 *
 * @package Aura.Filter
 *
 */
class FilterFactory
{
    protected $validate_rule_locator;

    protected $sanitize_rule_locator;

    /**
     *
     * Returns a new Filter instance.
     *
     * @return Filter
     *
     */
    public function newInstance()
    {
        return new Filter(
            $this->newValidateSpec(),
            $this->newSanitizeSpec()
        );
    }

    public function newValidateSpec()
    {
        return new ValidateSpec($this->getValidateRuleLocator());
    }

    public function newSanitizeSpec()
    {
        return new SanitizeSpec($this->getSanitizeRuleLocator());
    }

    public function getValidateRuleLocator()
    {
        if (! $this->validate_rule_locator) {
            $this->validate_rule_locator = new RuleLocator($this->getValidateFactories());
        }
        return $this->validate_rule_locator;
    }

    public function getSanitizeRuleLocator()
    {
        if (! $this->sanitize_rule_locator) {
            $this->sanitize_rule_locator = new RuleLocator($this->getSanitizeFactories());
        }
        return $this->sanitize_rule_locator;
    }

    public function getValidateFactories()
    {
        return [
            'alnum'                 => function () { return new Validate\Alnum; },
            'alpha'                 => function () { return new Validate\Alpha; },
            'between'               => function () { return new Validate\Between; },
            'blank'                 => function () { return new Validate\Blank; },
            'bool'                  => function () { return new Validate\Bool; },
            'closure'               => function () { return new Validate\Closure; },
            'creditCard'            => function () { return new Validate\CreditCard; },
            'dateTime'              => function () { return new Validate\DateTime; },
            'email'                 => function () { return new Validate\Email; },
            'equalToField'          => function () { return new Validate\EqualToField; },
            'equalToValue'          => function () { return new Validate\EqualToValue; },
            'float'                 => function () { return new Validate\Float; },
            'inKeys'                => function () { return new Validate\InKeys; },
            'int'                   => function () { return new Validate\Int; },
            'inValues'              => function () { return new Validate\InValues; },
            'ipv4'                  => function () { return new Validate\Ipv4; },
            'isbn'                  => function () { return new Validate\Isbn; }
            'locale'                => function () { return new Validate\Locale; }
            'max'                   => function () { return new Validate\Max; },
            'min'                   => function () { return new Validate\Min; },
            'regex'                 => function () { return new Validate\Regex; },
            'strictEqualToField'    => function () { return new Validate\StrictEqualToField; },
            'strictEqualToValue'    => function () { return new Validate\StrictEqualToValue; },
            'string'                => function () { return new Validate\String; },
            'strlen'                => function () { return new Validate\Strlen; },
            'strlenBetween'         => function () { return new Validate\StrlenBetween; },
            'strlenMax'             => function () { return new Validate\StrlenMax; },
            'strlenMin'             => function () { return new Validate\StrlenMin; },
            'trim'                  => function () { return new Validate\Trim; },
            'upload'                => function () { return new Validate\Upload; },
            'url'                   => function () { return new Validate\Url; },
            'word'                  => function () { return new Validate\Word; },
        ];
    }
}
