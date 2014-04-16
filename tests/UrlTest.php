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
        $this->assertEquals('8080', $u->port);
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
    
    public function testSettingChainingPathShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $u->path('something/other')->path('again');
        $this->assertEquals('http://username:password@hostname:8080/path/something/other/again?arg=value#anchor', "$u");
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
        $u->user = 'username';
        $u->pass = 'password';

        $this->assertEquals('http://username:password@hostname:8080/path?arg=value#anchor', "$u");
        
        $u = new Url('http://hostname:8080/path?arg=value#anchor');
        $u->user('username')->pass('password');

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
        $u->scheme = 'HTTPS';
        $this->assertEquals('https://hostname:8080/path#anchor', "$u");
        $u->scheme = 'https';
        $this->assertEquals('https://hostname:8080/path#anchor', "$u");
        $u->scheme = 'ftp';
        $this->assertEquals('ftp://hostname:8080/path#anchor', "$u");
        $u->scheme = 'https://';
        $this->assertEquals('https://hostname:8080/path#anchor', "$u");
        $u->scheme = 'ftp://';
        $this->assertEquals('ftp://hostname:8080/path#anchor', "$u");
        $u->scheme = 'svn+ssh';
        $this->assertEquals('svn+ssh://hostname:8080/path#anchor', "$u");
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingSchemeStartingWithNumberShouldFail()
    {
        $u = new Url('http://hostname:8080/path#anchor');
        $u->scheme = '9https';
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingSchemeHavingBagCharsShouldFail()
    {
        $u = new Url('http://hostname:8080/path#anchor');
        $u->scheme = 'https_écrit';
    }


    public function testSettingHostnameShouldSuccess()
    {
        $u = new Url('https://hostname:8080/path#anchor');
        $u->host = 'www.example.org';
        $this->assertEquals('https://www.example.org:8080/path#anchor', "$u");
        $u->host = 'localhost';
        $this->assertEquals('https://localhost:8080/path#anchor', "$u");
    }
    
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingHostnameWithPartGreaterThan63ShouldFail()
    {
        $u = new Url('https://hostname:8080/path#anchor');
        $u->host = 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingHostnameBiggerThan255ShouldFail()
    {
        $u = new Url('https://hostname:8080/path#anchor');
        $u->host = 'abcdefghijklmnopqrstuvwxyz01234-abcdefghijklmnopqrstuvwxyztoo-big.abcdefghijklmnopqrstuvwxyz01234-abcdefghijklmnopqrstuvwxyz01234.fr';
    }

    public function testSettingPortShouldSuccess()
    {
        $u = new Url('https://hostname/path#anchor');
        $u->port = 8080;
        $this->assertEquals('https://hostname:8080/path#anchor', "$u");
        $u->port = '1234';
        $this->assertEquals('https://hostname:1234/path#anchor', "$u");
        $u = new Url('https://hostname/path#anchor');
        $u->port->set(8080);
        $this->assertEquals('https://hostname:8080/path#anchor', "$u");
        $u->port->set('1234');
        $this->assertEquals('https://hostname:1234/path#anchor', "$u");
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingNegativePortShouldFail()
    {
        $u = new Url('https://hostname/path#anchor');
        $u->port = -8080;
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingTooBigPortShouldFail()
    {
        $u = new Url('https://hostname/path#anchor');
        $u->port = 65536;
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingNotNumericPortShouldFail()
    {
        $u = new Url('https://hostname/path#anchor');
        $u->port = 'azerty';
    }

    public function testGettingPortShouldSuccess()
    {
        $u = new Url('https://hostname:8080/path#anchor');
        $this->assertEquals('8080', $u->port);
        $this->assertEquals(8080, $u->port->get());
        $u = new Url('https://hostname/path#anchor');
        $this->assertEquals('', $u->port);
        $this->assertEquals(null, $u->port->get());
    }
    
    public function testSettingPortInChainContextShouldSuccess()
    {
        $u = new Url('https://hostname/path#anchor');
        $this->assertInstanceOf('\Malenki\Url\Url', $u->port(8080));
        $this->assertInstanceOf('\Malenki\Url\Url', $u->port('1234'));
        $this->assertEquals('https://hostname:8080/path#anchor', $u->port(8080));
        $this->assertEquals('https://hostname:1234/path#anchor', $u->port('1234'));
    }

    public function testSettingAnchorShouldSuccess()
    {
        $u = new Url('https://hostname/path#anchor');
        $u->anchor = 'something';
        $this->assertEquals('https://hostname/path#something', "$u");
        $u->fragment = 'other';
        $this->assertEquals('https://hostname/path#other', "$u");
        $u = new Url('https://hostname/path#anchor');
        $u->anchor->set('something');
        $this->assertEquals('https://hostname/path#something', "$u");
        $u->fragment->set('other');
        $this->assertEquals('https://hostname/path#other', "$u");
        $u = new Url('https://hostname/path#anchor');
        $u->anchor('something');
        $this->assertEquals('https://hostname/path#something', "$u");
        $u->fragment('other');
        $this->assertEquals('https://hostname/path#other', "$u");
    }
    
    public function testSettingChainingAnchorShouldSuccess()
    {
        $u = new Url('https://hostname/path#anchor');
        $this->assertInstanceOf('\Malenki\Url\Url', $u->anchor('something'));
        $this->assertInstanceOf('\Malenki\Url\Url', $u->fragment('other'));
    }

    public function testGettingAnchorShouldSuccess()
    {
        $u = new Url('https://hostname/path#anchor');
        $this->assertEquals('anchor', $u->anchor);
        $this->assertEquals('anchor', $u->fragment);
        $this->assertEquals('anchor', $u->anchor->get());
        $this->assertEquals('anchor', $u->fragment->get());
    }

    public function testAnchorIsVoidOrNotShouldSuccess()
    {
        $u = new Url('https://hostname/path#anchor');
        $this->assertFalse($u->anchor->isVoid());
        $this->assertFalse($u->fragment->isVoid());
        $u->anchor->clear;
        $this->assertTrue($u->anchor->isVoid());
        $this->assertTrue($u->fragment->isVoid());
        
        $u = new Url('https://hostname/path');
        $this->assertTrue($u->anchor->isVoid());
        $this->assertTrue($u->fragment->isVoid());
    }

    public function testClearFeatureOfUrlClassShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $u->clear();
        $this->assertEquals('http://hostname', "$u");
    }

    public function testChainingAllFeaturesShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $this->assertEquals('https://login:pwd@example.org:123/path/other/way?arg=value&foo=bar#something', (string) $u->scheme('https')->user('login')->pass('pwd')->host('example.org')->port(123)->path('other/way')->query(array('foo' => 'bar'))->anchor('something'));
    }
    
    public function testCloningUrlShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $v = clone $u;
        $this->assertEquals("$v", "$u");
        $this->assertEquals((string) $v->scheme, (string) $u->scheme);
        $this->assertEquals((string) $v->user, (string) $u->user);
        $this->assertEquals((string) $v->pass, (string) $u->pass);
        $this->assertEquals((string) $v->credential, (string) $u->credential);
        $this->assertEquals((string) $v->host, (string) $u->host);
        $this->assertEquals((string) $v->port, (string) $u->port);
        $this->assertEquals((string) $v->path, (string) $u->path);
        $this->assertEquals((string) $v->query, (string) $u->query);
        $this->assertEquals((string) $v->anchor, (string) $u->anchor);
        $this->assertEquals((string) $v->fragment, (string) $u->fragment);
        $u->scheme('https')->host('example.com')->credential('login:pwd')->path('other');
        $this->assertFalse((string) $v->scheme == (string) $u->scheme);
        $this->assertFalse((string) $v->host == (string) $u->host);
        $this->assertFalse((string) $v->credential == (string) $u->credential);
        $this->assertFalse((string) $v->path == (string) $u->path);
        $u->user('username')->pass('password');
        $this->assertTrue((string) $v->credential == (string) $u->credential);
    }
    
    public function testDisablingPartShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $this->assertEquals('http://username:password@hostname/path?arg=value', (string) $u->no('anchor')->no('port'));
        $this->assertEquals('http://hostname?arg=value', (string) $u->no_credential->no_path);
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $this->assertEquals('http://username:password@hostname/path?arg=value', (string) $u->no(array('anchor', 'port')));
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $this->assertEquals('http://username:password@hostname/path?arg=value', (string) $u->disable('anchor')->disable('port'));
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $this->assertEquals('http://username:password@hostname/path?arg=value', (string) $u->disable(array('anchor', 'port')));
        $this->assertEquals('http://hostname?arg=value', (string) $u->disable_credential->disable_path);
    }

    public function testHavingPartShouldSuccess()
    {
        $u = new Url('http://username:password@hostname:8080/path?arg=value#anchor');
        $this->assertTrue($u->has('anchor'));
        $this->assertTrue($u->has('port'));
        $this->assertTrue($u->has_credential);
        $this->assertTrue($u->has_path);
    }
}
