<?php
namespace Aura\Filter;
require_once dirname(__DIR__) . '/src.php';
$registry = [
    'alnum' => new Rule\Alnum,
    'alpha' => new Rule\Alpha,
    'between' => new Rule\Between,
    'blank' => new Rule\Blank,
    'bool' => new Rule\Bool,
    'countryCode' => new Rule\CountryCode,
    'creditCard' => new Rule\CreditCard,
    'email' => new Rule\Email,
    'equalToField' => new Rule\EqualToField,
    'equalToValue' => new Rule\EqualToValue,
    'float' => new Rule\Float,
    'inKeys' => new Rule\InKeys,
    'int' => new Rule\Int,
    'inValues' => new Rule\InValues,
    'ipv4' => new Rule\Ipv4,
    'isoDate' => new Rule\IsoDate,
    'isoTime' => new Rule\IsoTime,
    'isoTimestamp' => new Rule\IsoTimestamp,
    'max' => new Rule\Max,
    'min' => new Rule\Min,
    'regex' => new Rule\Regex,
    'strictEqualToField' => new Rule\StrictEqualToField,
    'strictEqualToValue' => new Rule\StrictEqualToValue,
    'string' => new Rule\String,
    'strlenBetween' => new Rule\StrlenBetween,
    'strlenMax' => new Rule\StrlenMax,
    'strlenMin' => new Rule\StrlenMin,
    'strlen' => new Rule\Strlen,
    'trim' => new Rule\Trim,
    'upload' => new Rule\Upload,
    'url' => new Rule\Url,
    'word' => new Rule\Word
];
return new Chain(new RuleLocator($registry));