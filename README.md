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

```php
$u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
$u->user('login')->host('example.org')->query(array('foo', 'bar'));
```

### Act on URL's parts

You have finest control using method relative to URL parts:

```php
$u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
$u->anchor->clear; // delete anchor
$u->path->add('other'); // add branch to path
$u->query->arg = 'other_value'; // Changed one arg of the query string
```
