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
