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

use \Malenki\Url\Port;

class PortTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithStringShouldSuccess()
    {
        $p = new Port('1234');
        $this->assertInstanceOf('\Malenki\Url\Port', $p);
        $p = new Port(8080);
        $this->assertInstanceOf('\Malenki\Url\Port', $p);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstanciateWithNegativeNumberShouldFail()
    {
        $p = new Port(-35);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstanciateWithTooBigNumberShouldFail()
    {
        $p = new Port(65536);
    }

    public function testToStringShouldSuccess()
    {
        $p = new Port('1234');
        $this->assertEquals('1234', "$p");
        $p = new Port(8080);
        $this->assertEquals('8080', "$p");
    }

    public function testSettingValueShouldSuccess()
    {
        $p = new Port();
        $p->set(8080);
        $this->assertEquals('8080', "$p");
        $this->assertEquals(8080, $p->get());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingNonNumericValueShouldFail()
    {
        $p = new Port();
        $p->set('azerty');
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingNegativeValueShouldFail()
    {
        $p = new Port();
        $p->set(-8080);
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingTooBigValueShouldFail()
    {
        $p = new Port();
        $p->set(65536);
    }


    public function testRangeFamilyShouldSuccess()
    {
        $p = new Port(80);
        $this->assertTrue($p->isSystem());
        $this->assertFalse($p->isRegistered());
        $this->assertFalse($p->isDpe());
        $p = new Port(8080);
        $this->assertFalse($p->isSystem());
        $this->assertTrue($p->isRegistered());
        $this->assertFalse($p->isDpe());
        $p = new Port(49153);
        $this->assertFalse($p->isSystem());
        $this->assertFalse($p->isRegistered());
        $this->assertTrue($p->isDpe());
    }
}
