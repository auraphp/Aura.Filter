<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter;

use Aura\Filter\Failure\FailureCollection;
use Aura\Filter\Locator\SanitizeLocator;
use Aura\Filter\Locator\ValidateLocator;
use Aura\Filter\Spec\SanitizeSpec;
use Aura\Filter\Spec\ValidateSpec;
use Aura\Filter\Spec\SubSpecFactory;

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
     *
     */
    public function newSubjectFilter(string $class = SubjectFilter::class): object
    {
        return new $class(
            $this->newValidateSpec(),
            $this->newSanitizeSpec(),
            $this->newSubSpecFactory(),
            $this->newFailureCollection()
        );
    }

    /**
     *
     * Returns a new ValueFilter instance.
     *
     *
     */
    public function newValueFilter(): ValueFilter
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
     *
     */
    public function newValidateSpec(): ValidateSpec
    {
        return new ValidateSpec($this->newValidateLocator());
    }

    /**
     *
     * Returns a new SanitizeSpec instance.
     *
     *
     */
    public function newSanitizeSpec(): SanitizeSpec
    {
        return new SanitizeSpec($this->newSanitizeLocator());
    }

    /**
     *
     * Returns a new SubSpecFactory instance.
     *
     *
     */
    public function newSubSpecFactory(): SubSpecFactory
    {
        return new SubSpecFactory($this);
    }

    /**
     *
     * Returns a new ValidateLocator instance.
     *
     *
     */
    public function newValidateLocator(): ValidateLocator
    {
        return new ValidateLocator($this->validate_factories);
    }

    /**
     *
     * Returns a new SanitizeLocator instance.
     *
     *
     */
    public function newSanitizeLocator(): SanitizeLocator
    {
        return new SanitizeLocator($this->sanitize_factories);
    }

    /**
     *
     * Returns a new FailureCollection instance.
     *
     *
     */
    public function newFailureCollection(): FailureCollection
    {
        return new FailureCollection();
    }
}
