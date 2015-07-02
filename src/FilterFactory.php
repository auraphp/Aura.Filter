<?php
/**
 *
 * This file is part of Aura for PHP.
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
    /**
     *
     * A locator for "validate" rules.
     *
     * @var ValidateLocator
     *
     */
    protected $validate_locator;

    /**
     *
     * A locator for "sanitize" rules.
     *
     * @var SanitizeLocator
     *
     */
    protected $sanitize_locator;

    /**
     *
     * A PDO instance for PDO-specific rules.
     *
     * @var PDO
     *
     */
    protected $pdo;

    /**
     *
     * The prefix to use when quoting identifier names.
     *
     * @var string
     *
     */
    protected $quote_name_prefix;

    /**
     *
     * The suffix to use when quoting identifier names.
     *
     * @var string
     *
     */
    protected $quote_name_suffix;

    /**
     *
     * Constructor; note that passing a PDO connection is optional.
     *
     * @param PDO $pdo A PDO database connection.
     *
     * @param string $quote_name_prefix The prefix to use when quoting identifier names.
     *
     * @param string $quote_name_suffix The suffix to use when quoting identifier names.
     *
     * @return self
     *
     */
    public function __construct(
        PDO $pdo = null,
        $quote_name_prefix = '"',
        $quote_name_suffix = '"'
    ) {
        $this->pdo = $pdo;
        $this->quote_name_prefix = $quote_name_prefix;
        $this->quote_name_suffix = $quote_name_suffix;
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

    /**
     *
     * Returns a new ValueFilter instance.
     *
     * @return ValueFilter
     *
     */
    public function newValueFilter()
    {
        return new ValueFilter(
            $this->getValidateLocator(),
            $this->getSanitizeLocator()
        );
    }

    /**
     *
     * Returns a new ValidateSpec instance.
     *
     * @return ValidateSpec
     *
     */
    protected function newValidateSpec()
    {
        return new ValidateSpec($this->getValidateLocator());
    }

    /**
     *
     * Returns a new SanitizeSpec instance.
     *
     * @return SanitizeSpec
     *
     */
    protected function newSanitizeSpec()
    {
        return new SanitizeSpec($this->getSanitizeLocator());
    }

    /**
     *
     * Returns a shared ValidateLocator instance.
     *
     * @return ValidateLocator
     *
     */
    public function getValidateLocator()
    {
        if (! $this->validate_locator) {
            $this->validate_locator = new ValidateLocator($this->getValidateFactories());
        }
        return $this->validate_locator;
    }

    /**
     *
     * Returns a shared SanitizeLocator instance.
     *
     * @return SanitizeLocator
     *
     */
    public function getSanitizeLocator()
    {
        if (! $this->sanitize_locator) {
            $this->sanitize_locator = new SanitizeLocator($this->getSanitizeFactories());
        }
        return $this->sanitize_locator;
    }

    /**
     *
     * Returns an array of "validate" rule factories.
     *
     * @return array
     *
     */
    protected function getValidateFactories()
    {
        $factories = array(
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

        $this->addValidatePdo($factories);
        return $factories;
    }

    /**
     *
     * If $pdo is set, adds PDO-specific rules to the "validate" factories.
     *
     * @param array $factories The "validate" factories.
     *
     * @return null
     *
     */
    protected function addValidatePdo(&$factories)
    {
        if (! $this->pdo) {
            return;
        }

        $pdo = $this->pdo;
        $quote_name_prefix = $this->quote_name_prefix;
        $quote_name_suffix = $this->quote_name_suffix;

        $factories['inTableColumn'] = function () use ($pdo, $quote_name_prefix, $quote_name_suffix) {
            return new Validate\InTableColumn($pdo, $quote_name_prefix, $quote_name_suffix);
        };
    }

    /**
     *
     * Returns an array of "sanitize" rule factories.
     *
     * @return array
     *
     */
    protected function getSanitizeFactories()
    {
        return array(
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
            'max'                   => function () { return new Sanitize\Max(); },
            'min'                   => function () { return new Sanitize\Min(); },
            'now'                   => function () { return new Sanitize\Now(); },
            'regex'                 => function () { return new Sanitize\Regex(); },
            'remove'                => function () { return new Sanitize\Remove(); },
            'strictEqualToField'    => function () { return new Sanitize\StrictEqualToField(); },
            'strictEqualToValue'    => function () { return new Sanitize\StrictEqualToValue(); },
            'string'                => function () { return new Sanitize\Str(); },
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
