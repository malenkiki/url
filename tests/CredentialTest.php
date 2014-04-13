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
use \Malenki\Url\Path;
use \Malenki\Url\Credential;

class CredentialTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithStringShouldSuccess()
    {
        $c = new Credential('malenki:S0m3tH1n9');
        $this->assertInstanceOf('\Malenki\Url\Credential', $c);
    }

    public function testSettingAllAndGetShouldSuccess()
    {
        $c = new Credential('malenki:S0m3:tH1n9');
        $this->assertEquals('malenki', $c->user);
        $this->assertEquals('S0m3:tH1n9', $c->pass);
    }


    public function testSettingByMagicSetterShouldSuccess()
    {
        $c = new Credential();
        $c->user = 'malenki';
        $c->pass = 'S0m3tH1n9';
        $this->assertEquals('malenki', $c->user);
        $this->assertEquals('S0m3tH1n9', $c->pass);
        $this->assertEquals('malenki:S0m3tH1n9', "$c");
    }

    public function testSettingByMethodShouldSuccess()
    {
        $c = new Credential();
        $c->user('malenki');
        $c->pass('S0m3tH1n9');
        $this->assertEquals('malenki', $c->user);
        $this->assertEquals('S0m3tH1n9', $c->pass);
        $this->assertEquals('malenki:S0m3tH1n9', "$c");
        
        $c = new Credential();
        $c->user('malenki')->pass('S0m3tH1n9');
        $this->assertEquals('malenki', $c->user);
        $this->assertEquals('S0m3tH1n9', $c->pass);
        $this->assertEquals('malenki:S0m3tH1n9', "$c");
    }

    public function testIfCredentialIsVoidOrNotShouldSuccess()
    {
        $c = new Credential();
        $this->assertTrue($c->isVoid());
        $c->user = 'somebody';
        $this->assertFalse($c->isVoid());
        $c->pass = 'pA5sW0rd';
        $this->assertFalse($c->isVoid());
        $c->clear();
        $this->assertTrue($c->isVoid());
    }
}