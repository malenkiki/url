Url
===

Handle URL with ease!

Quick example:

```php
$u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
echo $u->credential;
echo $u->host;

// you can do this using this way:
$u->credential->user = 'login';
$u->host = 'example.org'

// you can use other way too:
$u->user('login')->host('example.org');

// toString available:
echo $u;
```

You have finest control using method relative to URL parts:

```php
$u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
$u->anchor->clear; // delete anchor
$u->path->add('other'); // add branch to path
$u->query->arg = 'other_value'; // Changed one arg of the query string
```
