Available Rules
---------------

- `alnum`: Validates the value as alphanumeric only. Sanitizes to leave only
  alphanumeric characters. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'alnum');
     ```

- `alpha`: Validates the value as alphabetic only. Sanitizes to leave only
  alphabetic characters. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'alpha');
     ```

- `between`: Validates the value as being within or equal to a minimum and
  maximum value. Sanitizes so that values lower than the range are forced up
  to the minimum; values higher than the range are forced down to the maximum.
  Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'between', $min, $max);
     ```

- `blank`: Validates the value as being blank. Sanitizes to `null`. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'blank');
     ```

- `bool`: Validates the value as being a boolean, or a pseudo-boolean.
  Pseudo-true values include the strings '1', 'y', 'yes', and 'true';
  pseudo-false values include the strings '0', 'n', 'no', and 'false'.
  Sanitizes to a strict PHP boolean. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'bool');
     ```

- `creditCard`: Validates the value as being a credit card number. The value
  cannot be sanitized. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'creditCard');
     ```

- `dateTime`: Validates the value as representing a date and/or time. Sanitizes
  the value to a specified format, default `'Y-m-d H:i:s'`. Usage (note that
  this is to sanitize, not validate):

     ```php
     $filter->addSoftRule('field', $filter::FIX, 'dateTime', $format);
     ```

- `email`: Validates the value as being a properly-formed email address. The
  value cannot be sanitized. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'email');
     ```

- `equalToField`: Validates the value as loosely equal to the value of another
  field in the data object. Sanitizes to the value of that other field.
  Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'equalToField', 'other_field_name');
     ```

- `equalToValue`: Validates the value as loosely equal to a specified value.
  Sanitizes to the specified value. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'equalToValue', $other_value);
     ```

- `float`: Validates the value as representing a float. Sanitizes the value to
  transform it into a float; for weird strings, this may not be what you
  expect. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'float');
     ```

- `inKeys`: Validates that the value is loosely equal to a key in a given
  array. The value cannot be sanitized. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'inKeys', $array);
     ```

- `inValues`: Validates that the value is strictly equal to at least one value
  in a given array. The value cannot be sanitized. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'inValues', $array);
     ```

- `int`: Validates the value as representing an integer. Sanitizes the value by
  transforming it into an integer; for weird strings, this may not be what you
  expect. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'int');
     ```

- `ipv4`: Validates the value as an IPv4 address. The value cannot be
  sanitized. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'ipv4');
     ```

- `locale`: Validates the given value against a list of locale strings. Returns false if it is
not found. The value cannot be sanitized. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'locale');
     ```

- `max`: Validates the value as being less than or equal to a maximum. Sanitizes
  so that values higher than the maximum are forced down to the maximum.
  Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'max', $max);
     ```

- `min`: Validates the value as being greater than or equal to a minimum.
  Sanitizes so that values lower than the minimum are forced up to the
  minimum. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'min', $min);
     ```

- `regex`: Validates the value using `preg_match()`. Sanitizes the value using
  `preg_replace()`.

     ```php
     $filter->addSoftRule('field', $filter::IS, 'regex', $expr);
     ```

- `strictEqualToField`: Validates the value as strictly equal to the value of
  another field in the data object. Sanitizes to the value of that other field.
  Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'strictEqualToField', 'other_field_name');
     ```

- `strictEqualToValue`: Validates the value as strictly equal to a specified
  value. Sanitizes to the specified value. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'strictEqualToValue', $other_value);
     ```

- `string`: Validates the value can be represented by a string. Sanitizes the
  value by casting to a string and optionally using `str_replace().` Usage
  (note that this is to sanitize, not validate):

     ```php
     $filter->addSoftRule('field', $filter::FIX, 'string', $find, $replace);
     ```

- `strlen`: Validates the value has a specified length. Sanitizes the value
  to cut off longer values at the right, and `str_pad()` shorter ones. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'strlen', $len);
     ```

- `strlenBetween`: Validates the value length as being within or equal to a
  minimum and maximum value. Sanitizes the value to cut off values longer than
  the maximum, longer values at the right, and `str_pad()` shorter ones.
  Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'strlenBetween', $min, $max);
     ```

- `strlenMax`: Validates the value length as being no longer than a maximum.
  Sanitizes the value to cut off values longer than the maximum. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'strlenMax', $max);
     ```

- `strlenMin`: Validates the value length as being no shorter than a minimum.
  Sanitizes the value to `str_pad()` values shorter than the minimum. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'strlenMin', $min);
     ```

- `trim`: Validates the value is `trim()`med. Sanitizes the value to `trim()` it.
  Optionally specify characters to trim. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'trim', $chars);
     ```

- `upload`: Validates the value represents a PHP upload information array, and
  that the file is an uploaded file. The value cannot be sanitized. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'upload');
     ```

- `url`: Validates the value is a well-formed URL. The value cannot be
  sanitized. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'url');
     ```

- `word`: Validates the value as being composed only of word characters.
  Sanitizes the value to remove non-word characters. Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'word');
     ```

- `isbn`: Validates the value is a correct ISBN (International Standard Book Number). Usage:

     ```php
     $filter->addSoftRule('field', $filter::IS, 'isbn');
     ```

- `any`: Validates the value passes at least one of the rules. These rules
are the ones added in the rule locator.

     ```php
     $filter->addSoftRule('field', $filter::IS, 'any', [
             ['alnum'],
             ['email'],
             // more rules
         ]
     );
     ```

- `all`: Validates the value against a set of rules. These rules
should be added in the rule locator. You will not get separate error
messages for the rules it failed.

     ```php
     $filter->addSoftRule('field', $filter::IS, 'all', [
             // rules
         ]
     );
     ```

