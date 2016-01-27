Third beta release.

- (BRK) Due to new blank-checking in ValidateSpec::applyRule(), remove 'blank' validation rule, and add 'isBlank()' validation spec method.

- (FIX) Filter arguments using arrays, resources, and objects (including closures) no longer cause errors when creating the default filter message.

- (ADD) Validation now fails on missing (unset or null) fields.

- (DOC) Update documentation.
