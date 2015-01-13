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

use Aura\Filter\Rule\Sanitize;
use Aura\Filter\Rule\Validate;
use Aura\Filter\Rule\Locator\SanitizeLocator;
use Aura\Filter\Rule\Locator\ValidateLocator;
use Aura\Filter\Spec\SanitizeSpec;
use Aura\Filter\Spec\ValidateSpec;
use PDO;

/**
 *
 * Factory to create Filter objects.
 *
 * @package Aura.Filter
 *
 */
class FilterFactory
{
    protected $validate_locator;

    protected $sanitize_locator;

    protected $pdo;

    protected $quote_prefix;

    protected $quote_suffix;

    public function __construct(
        PDO $pdo = null,
        $quote_prefix = '"',
        $quote_suffix = '"'
    ) {
        $this->pdo = $pdo;
        $this->quote_prefix = $quote_prefix;
        $this->quote_suffix = $quote_suffix;
    }

    /**
     *
     * Returns a new Filter instance.
     *
     * @return Filter
     *
     */
    public function newFilter()
    {
        return new Filter($this->newValidateSpec(), $this->newSanitizeSpec());
    }

    public function newValueFilter()
    {
        return new ValueFilter(
            $this->getValidateLocator(),
            $this->getSanitizeLocator()
        );
    }

    protected function newValidateSpec()
    {
        return new ValidateSpec($this->getValidateLocator());
    }

    protected function newSanitizeSpec()
    {
        return new SanitizeSpec($this->getSanitizeLocator());
    }

    public function getValidateLocator()
    {
        if (! $this->validate_locator) {
            $this->validate_locator = new ValidateLocator($this->getValidateFactories());
        }
        return $this->validate_locator;
    }

    public function getSanitizeLocator()
    {
        if (! $this->sanitize_locator) {
            $this->sanitize_locator = new SanitizeLocator($this->getSanitizeFactories());
        }
        return $this->sanitize_locator;
    }

    protected function getValidateFactories()
    {
        $factories = array(
            'alnum'                 => function () { return new Validate\Alnum(); },
            'alpha'                 => function () { return new Validate\Alpha(); },
            'between'               => function () { return new Validate\Between(); },
            'blank'                 => function () { return new Validate\Blank(); },
            'bool'                  => function () { return new Validate\Bool(); },
            'closure'               => function () { return new Validate\Closure(); },
            'creditCard'            => function () { return new Validate\CreditCard(); },
            'dateTime'              => function () { return new Validate\DateTime(); },
            'email'                 => function () { return new Validate\Email(); },
            'equalToField'          => function () { return new Validate\EqualToField(); },
            'equalToValue'          => function () { return new Validate\EqualToValue(); },
            'float'                 => function () { return new Validate\Float(); },
            'inKeys'                => function () { return new Validate\InKeys(); },
            'int'                   => function () { return new Validate\Int(); },
            'inValues'              => function () { return new Validate\InValues(); },
            'ipv4'                  => function () { return new Validate\Ipv4(); },
            'isbn'                  => function () { return new Validate\Isbn(); },
            'locale'                => function () { return new Validate\Locale(); },
            'max'                   => function () { return new Validate\Max(); },
            'min'                   => function () { return new Validate\Min(); },
            'now'                   => function () { return new Validate\Now(); },
            'regex'                 => function () { return new Validate\Regex(); },
            'strictEqualToField'    => function () { return new Validate\StrictEqualToField(); },
            'strictEqualToValue'    => function () { return new Validate\StrictEqualToValue(); },
            'string'                => function () { return new Validate\String(); },
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

        $this->addValidatePdo($factories);
        return $factories;
    }

    protected function addValidatePdo(&$factories)
    {
        if (! $this->pdo) {
            return;
        }

        $pdo = $this->pdo;
        $quote_prefix = $this->quote_prefix;
        $quote_suffix = $this->quote_suffix;

        $factories['inTableColumn'] = function () use ($pdo, $quote_prefix, $quote_suffix) {
            return new Validate\InTableColumn($pdo, $quote_prefix, $quote_suffix);
        };
    }

    protected function getSanitizeFactories()
    {
        return array(
            'alnum'                 => function () { return new Sanitize\Alnum(); },
            'alpha'                 => function () { return new Sanitize\Alpha(); },
            'between'               => function () { return new Sanitize\Between(); },
            'bool'                  => function () { return new Sanitize\Bool(); },
            'closure'               => function () { return new Sanitize\Closure(); },
            'dateTime'              => function () { return new Sanitize\DateTime(); },
            'field'                 => function () { return new Sanitize\Field(); },
            'float'                 => function () { return new Sanitize\Float(); },
            'int'                   => function () { return new Sanitize\Int(); },
            'isbn'                  => function () { return new Sanitize\Isbn(); },
            'max'                   => function () { return new Sanitize\Max(); },
            'min'                   => function () { return new Sanitize\Min(); },
            'regex'                 => function () { return new Sanitize\Regex(); },
            'remove'                => function () { return new Sanitize\Remove(); },
            'strictEqualToField'    => function () { return new Sanitize\StrictEqualToField(); },
            'strictEqualToValue'    => function () { return new Sanitize\StrictEqualToValue(); },
            'string'                => function () { return new Sanitize\String(); },
            'strlen'                => function () { return new Sanitize\Strlen(); },
            'strlenBetween'         => function () { return new Sanitize\StrlenBetween(); },
            'strlenMax'             => function () { return new Sanitize\StrlenMax(); },
            'strlenMin'             => function () { return new Sanitize\StrlenMin(); },
            'trim'                  => function () { return new Sanitize\Trim(); },
            'uuid'                  => function () { return new Sanitize\Uuid(); },
            'uuidHexonly'           => function () { return new Sanitize\UuidHexonly(); },
            'value'                 => function () { return new Sanitize\Value(); },
            'word'                  => function () { return new Sanitize\Word(); },
        );
    }
}
