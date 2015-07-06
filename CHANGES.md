First 2.0 beta release.

- BREAK: Removed Validate\Ipv4 and Validate\Ipv6 in favor of allowing flags on
  Validate\Ip.

- BREAK: Removed Validate\InTableColumn entirely, as it requires a PDO connection. This is better implemented as part of a group of database-related filters, rather than as a special case herein.

- ADD: The FilterFactory now takes two constructor params, $validate_factories and $sanitize_factories, to allow injection of rule factories at construction time.

- BREAK: Renamed Filter to SubjectFilter.
