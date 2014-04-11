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


class Query implements \Countable
{
    protected $arr = array();


    public function __get($name)
    {
        if(array_key_exists($name, $this->arr))
        {
            return $this->arr[$name];
        }
        else
        {
            throw new \RuntimeException($name . ' arg does not exist!');
        }
    }


    public function __set($name, $value)
    {
        $this->arr[$name] =  $value;
    }


    public function __isset($name)
    {
        return $this->exists($name);
    }

    public function __unset($name)
    {
        if(array_key_exists($name, $this->arr))
        {
            unset($this->arr[$name]);
        }
        else
        {
            throw new \RuntimeException($name . ' arg does not exist! Cannot delete it.');
        }
    }


    public function __construct($query = null)
    {
        if(is_string($query))
        {
            parse_str(ltrim($query, '?'), $this->arr);
        }
        elseif(is_array($query))
        {
            $this->arr = $query;
        }
    }

    public function count()
    {
        return count($this->arr);
    }


    public function has($value)
    {
        return in_array($value, $this->arr);
    }


    public function exists($key)
    {
        return array_key_exists($key, $this->arr);
    }


    public function isVoid()
    {
        return $this->count() == 0;
    }

    public function __toString()
    {
        return http_build_query($this->arr);
    }
}
