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

use \Malenki\Url\Fragment;

class FragmentTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithStringShouldSuccess()
    {
        $f = new Fragment('foo');
        $this->assertInstanceOf('\Malenki\Url\Fragment', $f);
    }

    public function testIsVoidOrNotShouldSuccess()
    {
        $f = new Fragment('foo');
        $this->assertFalse($f->isVoid());
        $f = new Fragment();
        $this->assertTrue($f->isVoid());
    }

    public function testClearValueShouldSuccess()
    {
        $f = new Fragment('bar');
        $f->clear();
        $this->assertTrue($f->isVoid());
        $f = new Fragment('foo');
        $f->clear;
        $this->assertTrue($f->isVoid());
    }

    public function testSettingValueShouldSuccess()
    {
        $f = new Fragment('bar');
        $f->set('foo');
        $this->assertEquals('foo', "$f");
        $this->assertEquals('foo', $f->get());
    }
}
