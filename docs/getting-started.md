# Getting Started

## Terminology

Unfortunately, there are not many common terms shared between filtering/validating/etc. libraries. To clear up misconception, this library uses the following definitions:

- "filter": validate and/or sanitize one or more fields

- "validate": determine if a field value conforms to a particular format, but do not modify the field value

- "sanitize": modify, transform, or otherwise force a field value to conform to a particular format

This library also makes a distinction between a "value" filter and a "subject" filter:

- A "value" filter validates and sanitizes an individual value

- A "subject" filter validates and sanitzes the collection of elements in an array, or the collection of properties in an object. (The "subject" is the array or object.)

## Filter Container

The easiest way to interact with the filter system is via the _FilterFactory_. Instantiate it first; you can then get filter objects from it:

```php
use Aura\Filter\FilterFactory;

$filter_factory = new FilterFactory();

$filter = $filter_factory->newValueFilter();
$filter = $filter_factory->newSubjectFilter();

```
