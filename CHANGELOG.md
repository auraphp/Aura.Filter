# CHANGELOG

## 2.3.1

- (DOC) Update the documentation [128](https://github.com/auraphp/Aura.Filter/pull/128), [129](https://github.com/auraphp/Aura.Filter/pull/129)
- (FIX) PHP Notice when trying to sanitize a string to integer  [132](https://github.com/auraphp/Aura.Filter/issues/132)
- (ADD) 'phpunit/phpunit' as require-dev dependency in composer.json .
- (ADD) Added CHANGELOG.md file.
- (REMOVE) Removed CHANGES.md file.

## 2.3.0

- (ADD) Implement JsonSerializable in the Failure class
- (DOC) Update the documentation
- (FIX) Removed undefined but registered sanitizers from SanitizerLocator

## 2.2.0

This release adds new validation and sanitizing rules:

- `lowerCase` for all-lower-case values
- `upperCase` for all-upper-case values
- `titleCase` for title-cased values
- `lowerCaseFirst` for values where the first character is lower-case
- `upperCaseFirst` for values where the first character is upper-case

## 2.1.0

This release adds an `isNotBlank()` validation specifier, and fixes a bug where sanitizing `to()` a rule on a missing field raised a notice.

## 2.0.0

First stable release.

- (FIX) The Spec class now reports failures better when closures are used in HHVM.

- (TST) Improved testing.

## 2.0.0-beta3

Third beta release.

- (BRK) Due to new blank-checking in ValidateSpec::applyRule(), remove 'blank' validation rule, and add 'isBlank()' validation spec method.

- (FIX) Filter arguments using arrays, resources, and objects (including closures) no longer cause errors when creating the default filter message.

- (ADD) Validation now fails on missing (unset or null) fields.

- (DOC) Update documentation.

## 2.0.0-beta2

Second beta release.

- (ADD) Add UTF-8 support in Alpha, Alnum, Strlen*, and Word filters with help from @mivanov93.

    - All string-length filters are now multi-byte aware using either `mbstring` or `iconv` extensions.

    - In alnum and alpha rules, use unicode letters and digits instead of ctype.

    - In word rules, use unicode letters and digits instead of \w and \W.

- (ADD) More robust email validation based on is_email() from @dominicsayers, plus IDN support as suggested by @dg via the `intl` extension.

- (TEST) Update Travis-CI config to use containers.

- (DOCS) Update relevant documentation.

## 2.0.0-beta1

First 2.0 beta release.

- BREAK: Renamed class Filter to SubjectFilter.

- BREAK: Removed method SubjectFilter::strict() and all "strict" behavior, as get_object_vars() is not guaranteed in some objects (e.g. magic get/set vs public properties)

- BREAK: Replaced method SubjectFilter::getMessages() et al with getFailures(); failures are now reported as a FailureCollection instead of as an array of text messages.

- BREAK: Removed classes Rule\Validate\Ipv4 and Ipv6 in favor of allowing flags on
  Rule\Validate\Ip.

- BREAK: Removed class Rule\Validate\InTableColumn entirely, as it requires a PDO connection. This is better implemented as part of a group of database-related filters, rather than as a special case herein.

- BREAK: Moved namespace Rule\Locator to Locator.

- BREAK: Renamed class Spec\AbstractSpec to Spec\Spec.

- BREAK: Removed method Spec\Spec::getFailureMode().

- BREAK: Moved constants from SubjectFilter to Spec\Spec.

- BREAK: Removed methods ValueFilter::assert() and setExceptionClass().

- ADD: Class FilterFactory now takes two constructor params, $validate_factories and $sanitize_factories, to allow injection of rule factories at construction time.

- ADD: Class AbstractStaticFilter to allow users to create static value filters.

## 2.0.0-alpha1

First 2.0 dev release; breaking changes are coming.

## 1.1.0

- Add Translator::set() method, to set custom messages.
- Docblock and metadata corrections.

## 1.0.2

Hygiene release: docblock and Composer updates.

## 1.0.1

Hygeine release: point travis status badge to develop branch.

## 1.0.0

- [ADD] German translations; thanks @geissler

- [ADD] Rule\Isbn from @geissler

- [DOC] Docblock cleanup (mostly style, some typo fixes and return type
  additions)

- [ADD] RuleCollection::addMessages(), to complement Aura.Input
  Filter::addMessages(), per @harikt

- [CHG] Translator::translate() to deal with array values.

- [CHG] Rule\InKeys to add a 'keys' pseudo-param, for message translations

- [DOC] Fixed wrong parameter order in readme; thanks @chrissound

## 1.0.0-beta2

There are some BC breaks in this release, but it's a "Google" beta, so ...

- [NEW] Rule\All: the value must pass all of the specified sub-rules.

- [NEW] Rule\Any: the value must pass any one of a series of sub-rules.

- [NEW] Rule\Closure: pass a closure to filter the value.

- [NEW] Rule\Locale: the value must be a locale code.

- [NEW] Rule\Method: call a method on the value object being filtered so that
  it may filter itself.

- [CHG] Various typo fixes from pborrelli.

- [CHG] In all rules, make validate()/sanitize() public so they can be called
  directly from custom rules.

- [NEW] Add translation facility and internationalization messages.

- [REF] Remove StdClass typehints (objects don't descend from StdClass)

- [BRK] Remove property $message; replace with $message_map and $message_key.

- [ADD] Property $params to retain params passed to validate() and sanitize().

- [ADD] Methods setParams(), setMessageKey(), and getParams().

- [CHG] Method is(), isNot(), et. al. set the appropriate message key.

- [ADD] Method RuleCollection::useFieldMessage() to specify a single message
  to be used when a field fials one or more of its filter rules

- [BRK] Registry entries *must* be wrapped in a callable from now on

- [FIX] In Rule\DateTime, improve checking for valid dates

- [ADD] Method RuleCollection::setRule() so we can implement Aura.Input FilterInterface

- [CHG] For standalone scripts/instance.php usage, moved all rules to a rule
  registry located in scripts/registry.php


## 1.0.0-beta1

Initial release.
