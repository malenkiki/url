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

class QueryTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithStringShouldSuccess()
    {
        $q = new Query('arg=value');
        $this->assertInstanceOf('\Malenki\Url\Query', $q);
    }
    
    
    public function testInstanciateWithArrayShouldSuccess()
    {
        $q = new Query(array('arg' => 'value'));
        $this->assertInstanceOf('\Malenki\Url\Query', $q);
    }

    public function testCountingShouldSuccess()
    {
        $q = new Query('arg=value&arg2=value2');
        $this->assertEquals(2, count($q));
    }
    
    public function testGettingArgShouldSuccess()
    {
        $q = new Query('arg=value&arg2=value2');
        $this->assertEquals('value', $q->arg);
        $this->assertEquals('value2', $q->arg2);
    }
    
    public function testSettingArgShouldSuccess()
    {
        $q = new Query('arg=value&arg2=value2');
        $q->arg3 = 'value3';

        $this->assertEquals('value3', $q->arg3);
    }
    
    
    public function testIfArgExistsShouldSuccess()
    {
        $q = new Query('arg=value&arg2=value2');

        $this->assertTrue($q->exists('arg'));
        $this->assertTrue($q->exists('arg2'));
    }
    
    public function testIfValueExistsShouldSuccess()
    {
        $q = new Query('arg=value&arg2=value2');

        $this->assertTrue($q->has('value'));
        $this->assertTrue($q->has('value2'));
    }
    
    public function testIfQueryIsVoidOrNotShouldSuccess()
    {
        $q = new Query('arg=value&arg2=value2');
        $this->assertFalse($q->isVoid());
        $q = new Query();
        $this->assertTrue($q->isVoid());
    }
    
    public function testUsingRfc1738ShouldSuccess()
    {
        $q = new Query();
        $q->set('arg', 'some value');
        $this->assertEquals('arg=some+value', (string) $q->rfc1738);
    }
    
    public function testUsingRfc3986ShouldSuccess()
    {
        $q = new Query();
        $q->set('arg', 'some value');
        $this->assertEquals('arg=some%20value', (string) $q->rfc3986);
    }
    
    public function testUsingCustomSeparatorShouldSuccess()
    {
        $q = new Query();
        $q->set('arg', 'value')->set('arg2', 'value2')->separator('&amp;');
        $this->assertEquals('arg=value&amp;arg2=value2', (string) $q);
    }
}

