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

use \Malenki\Url\Host;

class HostTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithStringShouldSuccess()
    {
        $h = new Host('example.org');
        $this->assertInstanceOf('\Malenki\Url\Host', $h);
    }

    public function testIsVoidOrNotShouldSuccess()
    {
        $h = new Host('example.org');
        $this->assertFalse($h->isVoid());
        $h = new Host();
        $this->assertTrue($h->isVoid());
    }

    public function testClearValueShouldSuccess()
    {
        $h = new Host('example.org');
        $h->clear();
        $this->assertTrue($h->isVoid());
        $h = new Host('google.com');
        $h->clear;
        $this->assertTrue($h->isVoid());
    }

    public function testSettingValueShouldSuccess()
    {
        $h = new Host('example.org');
        $h->set('google.com');
        $this->assertEquals('google.com', "$h");
        $this->assertEquals('google.com', $h->get());
    }
}
