<?php
/*
Copyright (c) 2014 Michel Petit <petit.michel@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

use \Malenki\Url;

class UrlTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithStringShouldSuccess()
    {
        $u = new Url('https://github.com');
        $this->assertInstanceOf('\Malenki\Url', $u);
    }

    public function testGettingAllPartShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $this->assertEquals('http', $u->scheme);
        $this->assertEquals('username', $u->user);
        $this->assertEquals('password', $u->pass);
        $this->assertEquals('hostname', $u->host);
        $this->assertEquals(8080, $u->port);
        $this->assertEquals('/path', $u->path);
        $this->assertEquals('arg=value', $u->query);
        $this->assertEquals('anchor', $u->fragment);
        $this->assertEquals('anchor', $u->anchor);
        $this->assertEquals('username', $u->credential->user);
        $this->assertEquals('password', $u->credential->pass);
        $this->assertEquals('username:password', $u->credential->str);
    }
}