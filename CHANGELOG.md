# CHANGELOG

## 7.0.0

### Breaking Changes

- PHP 8.4+ is now required (up from PHP 5.4+ in 2.x).
- License changed from BSD to MIT.
- Removed Aura.Di integration (`config/Common.php` and `autoload.php`).
- Removed `tests/ContainerTest.php` and `config/Common.php`; the library is now DI-container-agnostic.
- `SubjectFilter::__invoke(array|object &$subject)` still mutates the subject: on success it writes the sanitized values back into `$subject`; on failure it throws `FilterFailed`. Use the new `apply()` instead to leave the subject untouched and receive a `FilterResultInterface` with the sanitized copy and failures.
- `SubjectFilter::assert()` now accepts `array|object` and throws `FilterFailed` on failure (unchanged signature, but now typed).
- `Failure` now implements `Aura\Filter_Interface\FailureInterface` instead of `JsonSerializable` directly (it still serializes to JSON via the interface).
- `SubjectFilter` now implements `Aura\Filter_Interface\SubjectFilterInterface`.
- `FilterFactory::newSubjectFilter()` now returns `SubjectFilterInterface` instead of the concrete class.
- All classes now use `declare(strict_types=1)` and full PHP 8+ type declarations throughout.
- Removed `libxml` dependency (was not used internally).
- CI moved from Travis CI to GitHub Actions.

### New Features

- ADD: `SubjectFilter::apply()` — immutable filter application that returns a `FilterResultInterface` containing the sanitized subject copy and any `FailureCollection`, without mutating the original subject.
- ADD: `SubjectFilter::subfilter(string $field, string $subClass = '')` — compose nested `SubjectFilter` instances for validating and sanitizing sub-objects and sub-arrays (multidimensional support). Sub-filter values that are objects (DTOs) are correctly preserved; arrays are round-tripped through `stdClass` so only `stdClass` nodes are converted, not plain array fields.
- ADD: `Spec\SubSpec` and `Spec\SubSpecFactory` — internal classes supporting nested sub-filter specifications.
- ADD: `SanitizeSpec::useBlankField(string $field_name)` — when the target field is blank, use the value from a different field as the replacement value.
- ADD: `Rule\Validate\UploadedFile` — validates a PSR-7 `UploadedFileInterface` value with configurable rules: `required`, `fileExtension`, `fileMedia`, `sizeMax`, `sizeMin`.
- ADD: `Rule\Sanitize\UploadedFileOrNull` — sanitizes a value to `null` if it is not a valid PSR-7 uploaded file (i.e. not an `UploadedFileInterface` or upload error is not `UPLOAD_ERR_OK`).
- ADD: `SubjectFilter::useFieldMessage(string $field, string $message)` — set a custom failure message for a specific field, overriding all per-rule messages.
- ADD: PHP 8.4, 8.5 support added to CI test matrix.
- ADD: `aura/filter-interface` package dependency introducing `SubjectFilterInterface`, `FilterResultInterface`, `FilterResult`, and `FailureInterface`.

### Fixes

- FIX: PHP 8.4 deprecation — explicit nullable type added to `substr` `$length` parameter in `AbstractStrlen`.
- FIX: PHP 8.1 deprecation — replaced deprecated `utf8_decode()` with `mb_convert_encoding()`.
- FIX: `idn_to_ascii()` deprecation — updated email validator to stop using `INTL_IDNA_VARIANT_2003`.
- FIX: Email validation now gracefully handles missing domain parts instead of raising errors.
- FIX: Type declarations and formatting corrected in `AbstractStrlen`.
- FIX: Missing property declarations added throughout rule classes to fix PHP 8.2 dynamic-property deprecation warnings.
- FIX: Array-type checks added where subjects could be passed as arrays to rule `__invoke()` methods.
- FIX: Shallow-cast bug in multidimensional filter support — only `stdClass` nodes are converted, preserving plain array fields passed to validators and sanitizers.

### Changes

- CHG: `Failure::jsonSerialize()` return type is now `array` (typed).
- CHG: All rule `__invoke()` signatures updated to use typed parameters and return types.
- CHG: `FailureCollection` refactored for PHP 8+ typed properties and method signatures.
- CHG: `FilterFactory` simplified — constructor now takes only `$validate_factories` and `$sanitize_factories` arrays, typed throughout.
- CHG: PHPDoc types corrected and expanded across all classes (via @koriym).
- CHG: Various documentation and README fixes.

## 2.3.1

- DOC: Update the documentation [#128](https://github.com/auraphp/Aura.Filter/pull/128), [#129](https://github.com/auraphp/Aura.Filter/pull/129).
- FIX: PHP notice when trying to sanitize a string to integer [#132](https://github.com/auraphp/Aura.Filter/issues/132).
- ADD: `phpunit/phpunit` as `require-dev` dependency in `composer.json`.
- ADD: Added `CHANGELOG.md` file.
- REMOVE: Removed `CHANGES.md` file.

## 2.3.0

- ADD: Implement `JsonSerializable` in the `Failure` class.
- DOC: Update the documentation.
- FIX: Removed undefined but registered sanitizers from `SanitizeLocator`.

## 2.2.0

This release adds new validation and sanitizing rules:

- `lowerCase` for all-lower-case values.
- `upperCase` for all-upper-case values.
- `titleCase` for title-cased values.
- `lowerCaseFirst` for values where the first character is lower-case.
- `upperCaseFirst` for values where the first character is upper-case.

## 2.1.0

This release adds an `isNotBlank()` validation specifier, and fixes a bug where sanitizing `to()` a rule on a missing field raised a notice.

## 2.0.0

First stable release.

- FIX: The `Spec` class now reports failures better when closures are used in HHVM.
- TST: Improved testing.
