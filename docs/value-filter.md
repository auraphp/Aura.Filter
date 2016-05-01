# Filtering Individual Values

You can filter an individual value by using a _ValueFilter_.

## Using A Value Filter

First, create a _ValueFilter_ object from the _FilterFactory_:

```php
$filter = $filter_factory->newValueFilter();
```

Then, to apply the filter, call its `validate()` and `sanitize()` methods. Supply the variable to be filtered, along with a rule name and any arguments for the rule. (These methods return `true` to indicate success, or `false` to indicate failure.)

```php
// the username must be alphanumeric,
// but not *only* numeric,
// between 6 and 10 characters long,
// and cast it to a string
$ok = $filter->validate($username, 'alnum')
   && ! $filter->validate($username, 'int')
   && $filter->validate($username, 'strlenBetween', 6, 10)
   && $filter->sanitize($username, 'string');
if (! $ok) {
    echo "The username is not valid.";
}

// the password must be at least 6 characters long, and must match a
// confirmation field
$ok = $filter->validate($password, 'strlenMin', 6)
   && $filter->validate($password, 'equalToValue', $password_confirm);
if (! $ok) {
    echo "The password is not valid.";
}
```

Note that while `validate()` will not change the value being filtered, `sanitize()` will modify the value (thus sanitizing it).

## Using A Static Value Filter

In general, the Aura project avoids using statics, and recommends against them in all but the most limited cases.  However, some developers are fine with the tradeoffs of using globally-available static methods. For these developers, this package provides an abstract implementation that acts as a static proxy to a _ValueFilter_ instance.

To use a static value filter, first extend the _AbstractStaticFilter_ with your own class name; this helps to deconflict between different static filters:

```php
use Aura\Filter\AbstractStaticFilter;

class MyStaticFilter extends AbstractStaticFilter
{
}
```

Then pass a new _ValueFilter_ into your static proxy class:

```php
MyStaticFilter::setInstance($filter_factory->newValueFilter());
```

> N.b.: You can set the instance only once; further calls will throw an exception.

Now you can use the static proxy as a global:

```php
use MyStaticFilter as Filter;

class CreateUserCommand
{
    protected $username;
    protected $password;

    public function __construct($username, $password, $password_confirm)
    {
        $ok = Filter::validate($username, 'alnum')
           && ! Filter::validate($username, 'int')
           && Filter::validate($username, 'strlenBetween', 6, 10)
           && Filter::sanitize($username, 'string');

        if (! $ok) {
            throw new Exception("The username is not valid.");
        }

        $ok = Filter::validate($password, 'strlenMin', 6)
           && Filter::validate($password, 'equalToValue', $password_confirm);
        if (! $ok) {
            throw new Exception("The password is not valid.");
        }

        $this->username = $username;
        $this->password = $password;
    }
}
```
