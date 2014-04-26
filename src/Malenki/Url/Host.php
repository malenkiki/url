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

class Host
{
    protected $value = null;

    public function __get($name)
    {
        if ($name == 'clear') {
            return $this->clear();
        }
    }

    public function __construct($str = null)
    {
        if ($str) {
            $this->set($str);
        }
    }

    public function set($str)
    {
        if (is_string($str)) {
            //TODO: urlencode ? http://be2.php.net/manual/en/function.rawurlencode.php#26869
            //255 max with separator! Cf. http://stackoverflow.com/questions/106179/regular-expression-to-match-hostname-or-ip-address
            $str = trim($str);
            $ip = "/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/";
            $name = "/^([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])(\.([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9]))*$/";
            if (strlen($str) > 0 && strlen($str) <= 255 && (preg_match($ip, $str) || preg_match($name, $str))) {
                $this->value = $str;
            } else {
                throw new \InvalidArgumentException('Invalid hostname provided');
            }
        } else {
            throw new \InvalidArgumentException('Hostname must be a string!');
        }
    }

    public function get()
    {
        return $this->value;
    }

    public function clear()
    {
        $this->value = null;

        return $this;
    }

    public function isVoid()
    {
        return is_null($this->value);
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}
