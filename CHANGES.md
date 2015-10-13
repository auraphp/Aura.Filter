Second beta release.

- (ADD) Add UTF-8 support in Alpha, Alnum, Strlen*, and Word filters with help from @mivanov93.

    - All string-length filters are now multi-byte aware using either `mbstring` or `iconv` extensions.

    - In alnum and alpha rules, use unicode letters and digits instead of ctype.

    - In word rules, use unicode letters and digits instead of \w and \W.

- (ADD) More robust email validation based on is_email() from @dominicsayers, plus IDN support as suggested by @dg via the `intl` extension.

- (TEST) Update Travis-CI config to use containers.

- (DOCS) Update relevant documentation.
