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

use \Malenki\Url\Url;
use \Malenki\Url\Query;

class UrlTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithStringShouldSuccess()
    {
        $u = new Url('https://github.com');
        $this->assertInstanceOf('\Malenki\Url\Url', $u);
    }

    public function testInstanciateWithArrayShouldSuccess()
    {
        $arr = array();
        $arr['scheme'] = 'https';
        $arr['host'] = 'github.com';
        $arr['path'] = '/malenkiki/url';
        $arr['useless'] = 'foo';
        $u = new Url($arr);
        $this->assertInstanceOf('\Malenki\Url\Url', $u);
    }
    
    public function testInstanciateWithObjectShouldSuccess()
    {
        $obj = new \stdClass();
        $obj->scheme = 'https';
        $obj->host = 'github.com';
        $obj->path = '/malenkiki/url';
        $obj->useless = 'foo';
        $u = new Url($obj);
        $this->assertInstanceOf('\Malenki\Url\Url', $u);
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
        $this->assertEquals('username:password', $u->credential);
    }

    public function testAddingArgShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $u->query->set('arg2', 'value2');
        $u->query->arg3 = 'value3';
        $this->assertEquals('http://username:password@hostname:8080/path?arg=value&arg2=value2&arg3=value3#anchor', "$u");
    }
    
    public function testResetingArgShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $u->query->clear();
        $this->assertEquals('http://username:password@hostname:8080/path#anchor', "$u");
    }
    
    public function testAddingPathShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $u->path->add('something');
        $u->path->add('other');
        $this->assertEquals('http://username:password@hostname:8080/path/something/other?arg=value#anchor', "$u");
    }
    
    public function testResetPathShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $u->path->clear();
        $this->assertEquals('http://username:password@hostname:8080?arg=value#anchor', "$u");
    }
    
    
    public function testResetCredentialsShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $u->credential->clear();

        $this->assertEquals('http://hostname:8080/path?arg=value#anchor', "$u");
    }
     

    
    public function testSettingCredentialsShouldSuccess()
    {
        $u = new Url('http://hostname:8080/path?arg=value#anchor');
        $u->credential = 'username:password';

        $this->assertEquals('http://username:password@hostname:8080/path?arg=value#anchor', "$u");
        $u = new Url('http://hostname:8080/path?arg=value#anchor');
        $u->credential->user = 'username';
        $u->credential->pass = 'password';

        $this->assertEquals('http://username:password@hostname:8080/path?arg=value#anchor', "$u");
        $u = new Url('http://hostname:8080/path?arg=value#anchor');
        $u->credential->user = 'username';

        $this->assertEquals('http://username@hostname:8080/path?arg=value#anchor', "$u");
        
        $u = new Url('http://hostname:8080/path?arg=value#anchor');
        $u->credential->user('username');
        $u->credential->pass('password');

        $this->assertEquals('http://username:password@hostname:8080/path?arg=value#anchor', "$u");
        $u = new Url('http://hostname:8080/path?arg=value#anchor');
        $u->credential->user('username');

        $this->assertEquals('http://username@hostname:8080/path?arg=value#anchor', "$u");
    }


    public function testSettingPathShouldSuccess()
    {
        $u = new Url('http://hostname:8080?arg=value#anchor');
        $u->path = 'branch/leaf';

        $this->assertEquals('http://hostname:8080/branch/leaf?arg=value#anchor', "$u");
        
        $u = new Url('http://hostname:8080?arg=value#anchor');
        $u->path->add('branch');
        $u->path->add('leaf');

        $this->assertEquals('http://hostname:8080/branch/leaf?arg=value#anchor', "$u");

        $u->path->clear();
        
        $this->assertEquals('http://hostname:8080?arg=value#anchor', "$u");
        
        $u->path = 'other';

        $this->assertEquals('http://hostname:8080/other?arg=value#anchor', "$u");

        $u->path->clear()->add('another');
        $this->assertEquals('http://hostname:8080/another?arg=value#anchor', "$u");

        $u->path->clear->add('something');
        $this->assertEquals('http://hostname:8080/something?arg=value#anchor', "$u");
    }
    
    public function testSettingQueryShouldSuccess()
    {
        $u = new Url('http://hostname:8080/path#anchor');
        $u->query = 'foo=bar';
        $this->assertEquals('http://hostname:8080/path?foo=bar#anchor', "$u");
        $u->query->clear();
        $u->query = '?foo=bar';
        $this->assertEquals('http://hostname:8080/path?foo=bar#anchor', "$u");
        $u->query->clear()->set('bar', 'foo');
        $this->assertEquals('http://hostname:8080/path?bar=foo#anchor', "$u");
        $u->query->clear->set('bar', 'foo');
        $this->assertEquals('http://hostname:8080/path?bar=foo#anchor', "$u");
        $u->query->clear->add('bar', 'foo');
        $this->assertEquals('http://hostname:8080/path?bar=foo#anchor', "$u");
        $u->query->add('some', 'thing');
        $this->assertEquals('http://hostname:8080/path?bar=foo&some=thing#anchor', "$u");
    }

    public function testSettingSchemeShouldSuccess()
    {
        $u = new Url('http://hostname:8080/path#anchor');
        $u->scheme = 'https';
        $this->assertEquals('https://hostname:8080/path#anchor', "$u");
        $u->scheme = 'ftp';
        $this->assertEquals('ftp://hostname:8080/path#anchor', "$u");
        $u->scheme = 'https://';
        $this->assertEquals('https://hostname:8080/path#anchor', "$u");
        $u->scheme = 'ftp://';
        $this->assertEquals('ftp://hostname:8080/path#anchor', "$u");
    }
}
