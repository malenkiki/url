# Url

Handle URL with ease!

## Introduction

To play with URL string in PHP, you have to deal with some functions, not easy at all. With my little lib, you can get/set/icomplete/avoid many part of an URL. So, quick example to give you some idea:

```php
$u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
echo $u->credential;
echo $u->host;

// you can change some parts using this way:
$u->credential->user = 'login';
$u->host = 'example.org'
$u->query->foo = 'bar';

// you can use other way too:
$u->user('login')->host('example.org')->query(array('foo', 'bar'));

// toString available:
echo $u; // http://login:password@example.org:8080/path?arg=value&foo=bar#anchor
```

## How to use it

You can handle URL using two ways:

 - chainable methods of Url class
 - call some methods on URL's parts to have finest control.

### Only URL class

#### Set values or complete thems

Each URL part can be called with its name as a chainable method, as you can see into the following example:

```php
$u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
$u->user('login')->host('example.org')->query(array('foo', 'bar'));
```
You must notice that methods `path()` and `query()` do not remove original content, but complete it with value given as argument. You must disable respective part before, like explained into next part.

#### Disable parts or test their availability

Some more actions are available too, by using methods `no()`, `disable()` and `has()`:

 - `no()` and `disable()` are both the same effect, one is alias of other, and take one argument, a string or an array of string. Argument must contain an URL part (`scheme`, `port`…). So, `$u->no('port')` disables port part and `$u->disable(array('query', 'path'))` removes query and path parts from the URL.
 - `has()` tests whether a part is available into the URL or not. Simple, you give the name of the part to test and the method return `true` if it finds the part filled.

All methods explained into this section can be called using magic getter by using method name as prefix, followed by underscore and the part's name, so, you can do this for example:

```php
echo $u->no_port->disable_credential;
var_dump($u->has_port);
```

### Act on URL's parts

You have finest control using method relative to URL parts:

```php
$u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
$u->anchor->clear; // delete anchor
$u->path->add('other'); // add branch to path
$u->query->arg = 'other_value'; // Changed one arg of the query string
```

#### Common methods

Each part has at least this methods:

 - `isVoid()` to test whether the part has content or not
 - `clear()` to avoid the part
 - `toString()` is available

#### Set in one shot

You can set part directly (override original content):

```php
$u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
$u->path = 'other/path'; //or using array
$u->anchor = 'new_anchor';
```


