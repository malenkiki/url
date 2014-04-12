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

class Credential
{
    protected $user = null;
    protected $pass = null;

    public function __get($name)
    {
        if(in_array($name, array('user', 'pass')))
        {
            return $this->$name;
        }
    }

    public function __set($name, $value)
    {
        if(in_array($name, array('user', 'pass')))
        {
            return $this->$name($value);
        }
    }

    public function __construct($str = null)
    {
        if($str)
        {
            $arr = parse_url(sprintf('http://%s@bidon.org', $str));
            
            if(isset($arr['user']) && isset($arr['pass']))
            {
                $this->user = $arr['user'];
                $this->pass = $arr['pass'];
            }
            else
            {
                throw new \RuntimeException('Cannot set credential from given string!');
            }
        }
    }

    public function user($value)
    {
        if($value)
        {
            $this->user = $value;
        }
        return $this;
    }


    public function pass($value)
    {
        if($value)
        {
            $this->pass = $value;
        }
        return $this;
    }


    public function clear()
    {
        $this->user = null;
        $this->pass = null;

        return $this;
    }


    public function __toString()
    {
        if($this->user)
        {
            return $this->user . ($this->pass ?  ':' . $this->pass : '');
        }

        return '';
    }
}
