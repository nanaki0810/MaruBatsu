## Whtat's this?

This is the sample codes for students in a php course.

## How to try?

1. clone the repository. 
2. `php -S 0.0.0.0:10080` (PHP >=5.4).
3. access to http://localhost/00_file.php and the other sample.

## Contents

- 00_file.php
    - no use database.
    - learn:
        - how to use a file storage in simple(`file_put_contents()` and `file_get_contents()`).
        - what's the `serialize()` and `unserialize()`.
    - review:
        - setter/getter pattern with `__set()` and `__get()` magic methods.
        - basic lambda expression.
        - basic exception.
        - http response header, `header()` and MIME.

- 01_sqlite.php
    - use the SQLite3 with PDO.
    - learn:
        - what's PDO.
        - how to use PDO with SQLite3.
    - review:
        - operator `+` behavior with array parameters.
        - how to use SQLite3 basically.

## License

CC0

## Reference

- http://php.net/
    - http://www.php.net/manual/
- http://www.sqlite.org/
    - http://www.sqlite.org/docs.html
