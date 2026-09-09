# Upgrade Guide: 2.x → 7.0.0

This guide covers the breaking changes introduced in 7.0.0 and how to update your code accordingly.

---

## PHP Version Requirement

**2.x required PHP >= 5.4. 7.0.0 requires PHP >= 8.4.**

Ensure your runtime and CI pipeline are running PHP 8.4 or later before upgrading.

---

## License Change

The license has changed from **BSD** to **MIT**. Update any attribution or license references in your project accordingly.

---

## Removed: Aura.Di Integration

The `config/Common.php` container configuration file and the legacy `autoload.php` bootstrap file have been removed.

**Before (2.x):**
```php
// Using Aura.Di with config/Common.php
$di->params['Aura\Filter\FilterFactory'] = [
    'validate_factories'  => [],
    'sanitize_factories'  => [],
];
```

**After (7.0.0):**

Instantiate `FilterFactory` directly. No container config is provided by the package; wire it yourself with whichever DI container you use:

```php
use Aura\Filter\FilterFactory;

$factory = new FilterFactory(
    [], // validate factories
    []  // sanitize factories
);
$filter = $factory->newSubjectFilter();
```

---

## `SubjectFilter`: `apply()` Now Returns a `FilterResultInterface`

In 2.x, `apply()` mutated the subject in place and returned a `bool`. In 7.0.0, `apply()` is **immutable** — it never mutates your original subject. Instead, it returns a `FilterResultInterface` containing the sanitized copy and any failures.

**Before (2.x):**
```php
if ($filter->apply($subject)) {
    // $subject has been sanitized in place
} else {
    $failures = $filter->getFailures();
}
```

**After (7.0.0):**
```php
$result = $filter->apply($subject);

if ($result->isSuccess()) {
    $sanitized = $result->getValues(); // sanitized copy — original $subject unchanged
} else {
    $failures = $result->getFailures(); // FailureCollection
}
```

> `getFailures()` no longer exists as a standalone method on `SubjectFilter`. Failures are accessed via the `FilterResultInterface` returned by `apply()`.

---

## `SubjectFilter::__invoke()` Behaviour Change

In 2.x, `__invoke()` was equivalent to `assert()` — it mutated the subject and threw `FilterFailed` on failure (returning `null` on success).

In 7.0.0, `__invoke()` still mutates the subject (writing sanitized values back) and throws `FilterFailed` on failure, but internally it now uses `apply()`. The visible behaviour when catching `FilterFailed` remains the same.

If you were calling `$filter($subject)` and then `$filter->getFailures()` after catching the exception, use `$e->getFailures()` instead:

```php
try {
    $filter($subject);
} catch (\Aura\Filter\Exception\FilterFailed $e) {
    $failures = $e->getFailures(); // same as before
}
```

---

## `SubjectFilter::getFailures()` Removed

The `getFailures()` method on `SubjectFilter` has been removed. Failures are now returned via `FilterResultInterface::getFailures()`.

**Before (2.x):**
```php
if (! $filter->apply($subject)) {
    $failures = $filter->getFailures();
    foreach ($failures->getMessages() as $field => $messages) {
        // ...
    }
}
```

**After (7.0.0):**
```php
$result = $filter->apply($subject);
if (! $result->isSuccess()) {
    $failures = $result->getFailures();
    foreach ($failures->getMessages() as $field => $messages) {
        // ...
    }
}
```

---

## `SubjectFilter` Implements `SubjectFilterInterface`

`SubjectFilter` now declares `implements Aura\Filter_Interface\SubjectFilterInterface`. Type-hint against the interface when injecting filters:

```php
use Aura\Filter_Interface\SubjectFilterInterface;

class MyService
{
    public function __construct(private SubjectFilterInterface $filter) {}
}
```

---

## New Dependency: `aura/filter-interface`

The package now requires `aura/filter-interface` (currently at `7.x`). This introduces:

| Interface / Class | Description |
|---|---|
| `SubjectFilterInterface` | Implemented by `SubjectFilter` |
| `FilterResultInterface` | Returned by `apply()` |
| `FilterResult` | Concrete implementation of `FilterResultInterface` |
| `FailureInterface` | Implemented by `Failure` |

---

## `Failure` Now Implements `FailureInterface`

`Failure` previously implemented `JsonSerializable` directly. It now implements `Aura\Filter_Interface\FailureInterface`, which itself extends `JsonSerializable`. Existing `jsonSerialize()` calls continue to work without changes.

---

## Strict Types Throughout

All classes now declare `strict_types=1` and use typed property declarations and typed method signatures. If you extended any Aura.Filter class and overrode methods without matching type signatures, you will receive a fatal error.

**Example — update your overrides:**
```php
// Before (2.x override)
public function getField()
{
    return $this->field;
}

// After (7.0.0 compatible override)
public function getField(): string
{
    return $this->field;
}
```

---

## New Feature: Sub-Filters (Nested / Multidimensional Filtering)

7.0.0 introduces first-class support for filtering nested objects and arrays via `SubjectFilter::subfilter()`.

```php
$filter = $factory->newSubjectFilter();

// The 'address' field is itself filtered by an AddressFilter
$addressFilter = $filter->subfilter('address', AddressFilter::class);
$addressFilter->validate('city')->is('string');
$addressFilter->validate('zip')->is('strlen', 5, 5);

$result = $filter->apply($subject);
```

Sub-filter failures are reported using dotted field paths (e.g. `address.city`).

---

## New Feature: PSR-7 Uploaded File Rules

Two new rules support `psr/http-message` uploaded files:

| Rule | Type | Description |
|---|---|---|
| `Rule\Validate\UploadedFile` | Validate | Validates a `UploadedFileInterface`; supports `required`, `fileExtension`, `fileMedia`, `sizeMax`, `sizeMin` checks. |
| `Rule\Sanitize\UploadedFileOrNull` | Sanitize | Sanitizes to `null` if value is not a valid uploaded file. |

```php
$filter->validate('avatar')->is('uploadedFile')->required()->fileExtension(['jpg', 'png']);
$filter->sanitize('avatar')->to('uploadedFileOrNull');
```

---

## New Feature: `SanitizeSpec::useBlankField()`

When a field is blank, copy the value from another field instead of using a hard-coded blank value:

```php
// If 'display_name' is blank, use the value from 'username'
$filter->sanitize('display_name')->toBlankOr('trim')->useBlankField('username');
```

---

## New Feature: `SubjectFilter::useFieldMessage()`

Override all per-rule failure messages for a field with a single custom message:

```php
$filter->validate('email')->is('email');
$filter->useFieldMessage('email', 'Please enter a valid email address.');
```

---

## Removed: `autoload.php`

The package no longer ships a stand-alone `autoload.php`. Use Composer's autoloader exclusively:

```php
require __DIR__ . '/vendor/autoload.php';
```
