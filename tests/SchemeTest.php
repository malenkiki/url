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

use \Malenki\Url\Scheme;

class SchemeTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithStringShouldSuccess()
    {
        $s = new Scheme('http');
        $this->assertInstanceOf('\Malenki\Url\Scheme', $s);
        $s = new Scheme('svn+ssh');
        $this->assertInstanceOf('\Malenki\Url\Scheme', $s);
        $s = new Scheme('foo.bar');
        $this->assertInstanceOf('\Malenki\Url\Scheme', $s);
        $s = new Scheme('thing-other');
        $this->assertInstanceOf('\Malenki\Url\Scheme', $s);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstanciateWithBadStringShouldfail()
    {
        $s = new Scheme('http/truc');
    }

    public function testIsVoidOrNotShouldSuccess()
    {
        $s = new Scheme('https');
        $this->assertFalse($s->isVoid());
        $s = new Scheme();
        $this->assertTrue($s->isVoid());
    }

    public function testClearValueShouldSuccess()
    {
        $s = new Scheme('ftp');
        $s->clear();
        $this->assertTrue($s->isVoid());
        $s = new Scheme('ssh');
        $s->clear;
        $this->assertTrue($s->isVoid());
    }

    public function testSettingValueShouldSuccess()
    {
        $s = new Scheme('http');
        $this->assertEquals('http', "$s");
        $this->assertEquals('http', $s->get());
        $s->set('ftp');
        $this->assertEquals('ftp', "$s");
        $this->assertEquals('ftp', $s->get());
        $s->set('svn+ssh');
        $this->assertEquals('svn+ssh', "$s");
        $this->assertEquals('svn+ssh', $s->get());
        $s->set('foo.bar');
        $this->assertEquals('foo.bar', "$s");
        $this->assertEquals('foo.bar', $s->get());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingBadValueShouldFail()
    {
        $s = new Scheme();
        $s->set('http foo');
    }
}
