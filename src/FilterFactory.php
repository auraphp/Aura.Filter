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
use Aura\Filter\Locator\SanitizeLocator;
use Aura\Filter\Locator\ValidateLocator;
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
     * @return SubjectFilter
     *
     */
    public function newSubjectFilter($class = 'Aura\Filter\SubjectFilter')
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
            $this->newValidateLocator(),
            $this->newSanitizeLocator()
        );
    }

    /**
     *
     * Returns a new ValidateSpec instance.
     *
     * @return ValidateSpec
     *
     */
    public function newValidateSpec()
    {
        return new ValidateSpec($this->newValidateLocator());
    }

    /**
     *
     * Returns a new SanitizeSpec instance.
     *
     * @return SanitizeSpec
     *
     */
    public function newSanitizeSpec()
    {
        return new SanitizeSpec($this->newSanitizeLocator());
    }

    /**
     *
     * Returns a new ValidateLocator instance.
     *
     * @return ValidateLocator
     *
     */
    public function newValidateLocator()
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
    public function newSanitizeLocator()
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
    public function newFailureCollection()
    {
        return new FailureCollection();
    }
}
