<?php
/*
 * Copyright (c) 2014 Michel Petit <petit.michel@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Malenki\Url;

class Port
{
    protected $value = null;

    public function __get($name)
    {
        if ($name == 'clear') {
            return $this->clear();
        }

        if (in_array($name, array('system', 'registered', 'dpe'))) {
            $method = 'is'. ucfirst($name);

            return $this->$method();
        }
    }

    public function __construct($n = null)
    {
        if ($n) {
            $this->set($n);
        }
    }

    public function isSystem()
    {
        return $this->value >= 0 &&  $this->value <= 1023;
    }

    public function isRegistered()
    {
        return $this->value >= 1024 &&  $this->value <= 49151;
    }

    /**
     * Dynamic, private or ephemeral port?
     *
     * @access public
     * @return boolean
     */
    public function isDpe()
    {
        return $this->value >= 49152 &&  $this->value <= 65535;
    }

    public function set($n)
    {
        if (is_numeric($n)) {
            $n = (int) $n;

            if ($n < 0) {
                throw new \InvalidArgumentException('Port number must be positive or null integer');
            }

            if ($n > 65535) {
                throw new \InvalidArgumentException('Port number cannot be greater than 65535');
            }

            $this->value = $n;
        } else {
            throw new \InvalidArgumentException('Port number must be… a number!');
        }

        return $this;
    }

    public function get()
    {
        return $this->value;
    }

    public function isVoid()
    {
        return is_null($this->value);
    }

    public function clear()
    {
        $this->value = null;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}
