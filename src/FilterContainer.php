<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter;

use Aura\Filter\Failure\FailureCollection;
use Aura\Filter\Rule\Locator\SanitizeLocator;
use Aura\Filter\Rule\Locator\ValidateLocator;
use Aura\Filter\Rule\Sanitize;
use Aura\Filter\Rule\Validate;
use Aura\Filter\Spec\SanitizeSpec;
use Aura\Filter\Spec\ValidateSpec;

/**
 *
 * Container to create and retain Filter objects.
 *
 * @package Aura.Filter
 *
 */
class FilterContainer
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
     * Additional factories for the ValidateLocator.
     *
     * @var array
     *
     */
    protected $validate_factories;

    /**
     *
     * Additional factories for the SanitizeLocator.
     *
     * @var array
     *
     */
    protected $sanitize_factories;

    /**
     *
     * Constructor.
     *
     * @param array $validate_factories Additional factories for the ValidateLocator.
     *
     * @param array $sanitize_factories Additional factories for the SanitizeLocator.
     *
     * @return self
     *
     */
    public function __construct(
        array $validate_factories = array(),
        array $sanitize_factories = array()
    ) {
        $this->validate_factories = $validate_factories;
        $this->sanitize_factories = $sanitize_factories;
    }

    /**
     *
     * Returns a new Filter instance.
     *
     * @param string $class The filter class to instantiate.
     *
     * @return Filter
     *
     */
    public function newFilter($class = 'Aura\Filter\Filter')
    {
        return new $class(
            $this->newValidateSpec(),
            $this->newSanitizeSpec(),
            $this->newFailureCollection()
        );
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
            $this->validate_locator = $this->newValidateLocator();
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
            $this->sanitize_locator = $this->newSanitizeLocator();
        }
        return $this->sanitize_locator;
    }

    /**
     *
     * Returns a new ValidateLocator instance.
     *
     * @return ValidateLocator
     *
     */
    protected function newValidateLocator()
    {
        return new ValidateLocator($this->validate_factories);
    }

    /**
     *
     * Returns a new SanitizeLocator instance.
     *
     * @return SanitizeLocator
     *
     */
    protected function newSanitizeLocator()
    {
        return new SanitizeLocator($this->sanitize_factories);
    }

    /**
     *
     * Returns a new FailureCollection instance.
     *
     * @return FailureCollection
     *
     */
    protected function newFailureCollection()
    {
        return new FailureCollection();
    }
}
