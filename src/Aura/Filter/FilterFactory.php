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

/**
 *
 * Factory to create Filter objects.
 *
 * @package Aura.Filter
 *
 */
class FilterFactory
{
    /**
     *
     * @var TranslatorInterface
     *
     */
    protected $translator;

    /**
     *
     * Returns a new Filter instance.
     *
     * @return RuleCollection
     *
     */
    public function newInstance()
    {
        return new RuleCollection(
            new RuleLocator(array_merge(
                $this->registry(),
                ['any' => function () {
                    $rule = new Rule\Any;
                    $rule->setRuleLocator(new RuleLocator(
                        $this->registry()
                    ));

                    return $rule;
                }]
                )
            ),
            $this->getTranslator()
        );
    }

    public function getTranslator()
    {
        if (! $this->translator) {
            $this->translator = new Translator(
                require dirname(dirname(dirname(__DIR__))) . '/intl/en_US.php'
            );
        }
        return $this->translator;
    }

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function registry()
    {
        return [
            'alnum'                 => function() { return new Rule\Alnum; },
            'alpha'                 => function() { return new Rule\Alpha; },
            'between'               => function() { return new Rule\Between; },
            'blank'                 => function() { return new Rule\Blank; },
            'bool'                  => function() { return new Rule\Bool; },
            'creditCard'            => function() { return new Rule\CreditCard; },
            'dateTime'              => function() { return new Rule\DateTime; },
            'email'                 => function() { return new Rule\Email; },
            'equalToField'          => function() { return new Rule\EqualToField; },
            'equalToValue'          => function() { return new Rule\EqualToValue; },
            'float'                 => function() { return new Rule\Float; },
            'inKeys'                => function() { return new Rule\InKeys; },
            'int'                   => function() { return new Rule\Int; },
            'inValues'              => function() { return new Rule\InValues; },
            'ipv4'                  => function() { return new Rule\Ipv4; },
            'max'                   => function() { return new Rule\Max; },
            'min'                   => function() { return new Rule\Min; },
            'regex'                 => function() { return new Rule\Regex; },
            'strictEqualToField'    => function() { return new Rule\StrictEqualToField; },
            'strictEqualToValue'    => function() { return new Rule\StrictEqualToValue; },
            'string'                => function() { return new Rule\String; },
            'strlenBetween'         => function() { return new Rule\StrlenBetween; },
            'strlenMax'             => function() { return new Rule\StrlenMax; },
            'strlenMin'             => function() { return new Rule\StrlenMin; },
            'strlen'                => function() { return new Rule\Strlen; },
            'trim'                  => function() { return new Rule\Trim; },
            'upload'                => function() { return new Rule\Upload; },
            'url'                   => function() { return new Rule\Url; },
            'word'                  => function() { return new Rule\Word; },
            'isbn'                  => function() { return new Rule\Isbn; },
        ];
    }
}
