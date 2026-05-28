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
use Aura\Filter_Interface\SubjectFilterInterface;
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
     * Additional factories for the ValidateLocator.
     *
     * @var array
     */
    protected array $validate_factories;

    /**
     * Additional factories for the SanitizeLocator.
     *
     * @var array
     */
    protected array $sanitize_factories;

    public function __construct(
        array $validate_factories = [],
        array $sanitize_factories = []
    ) {
        $this->validate_factories = $validate_factories;
        $this->sanitize_factories = $sanitize_factories;
    }

    public function newSubjectFilter(string $class = SubjectFilter::class): SubjectFilterInterface
    {
        return new $class(
            $this->newValidateSpec(),
            $this->newSanitizeSpec(),
            $this->newSubSpecFactory(),
            $this->newFailureCollection()
        );
    }

    public function newValueFilter(): ValueFilter
    {
        return new ValueFilter(
            $this->newValidateLocator(),
            $this->newSanitizeLocator()
        );
    }

    public function newValidateSpec(): ValidateSpec
    {
        return new ValidateSpec($this->newValidateLocator());
    }

    public function newSanitizeSpec(): SanitizeSpec
    {
        return new SanitizeSpec($this->newSanitizeLocator());
    }

    public function newSubSpecFactory(): SubSpecFactory
    {
        return new SubSpecFactory($this);
    }

    public function newValidateLocator(): ValidateLocator
    {
        return new ValidateLocator($this->validate_factories);
    }

    public function newSanitizeLocator(): SanitizeLocator
    {
        return new SanitizeLocator($this->sanitize_factories);
    }

    public function newFailureCollection(): FailureCollection
    {
        return new FailureCollection();
    }
}
