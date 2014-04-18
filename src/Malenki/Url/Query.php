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
    protected $rfc = null;
    protected $separator = '&';


    public function __get($name)
    {
        if($name == 'clear')
        {
            return $this->clear();
        }

        if($name == 'rfc1738')
        {
            return $this->rfc(PHP_QUERY_RFC1738);
        }

        if($name == 'rfc3986')
        {
            return $this->rfc(PHP_QUERY_RFC3986);
        }

        return $this->get($name);
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
        $this->remove($name);
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



    public function get($name)
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



    public function set($name, $value)
    {
        $this->arr[$name] =  $value;

        return $this;
    }



    public function add($name, $value)
    {
        return $this->set($name, $value);
    }



    public function remove($name)
    {
        if(array_key_exists($name, $this->arr))
        {
            unset($this->arr[$name]);
        }
        else
        {
            throw new \RuntimeException($name . ' arg does not exist! Cannot delete it.');
        }

        return $this;
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


    public function merge($arr)
    {
        return new self(array_merge($this->arr, $arr));
    }


    public function clear()
    {
        $this->arr = array();

        return $this;
    }



    public function rfc($rfc = 'RFC1738')
    {
        if(version_compare(PHP_VERSION, '5.4.0', '>='))
        {
            if( $rfc === PHP_QUERY_RFC1738 || $rfc == 'RFC1738' || $rfc == '1738')
            {
                $this->rfc = PHP_QUERY_RFC1738;
            }
            elseif( $rfc === PHP_QUERY_RFC3986 || $rfc == 'RFC3986' || $rfc == '3986')
            {
                $this->rfc = PHP_QUERY_RFC3986;
            }
            else
            {
                throw new \InvalidArgumentException('Bad RFC name!');
            }
        }

        return $this;
    }



    public function separator($str = '&')
    {
        $this->separator = $str;

        return $this;
    }



    public function __toString()
    {
        if(count($this->arr))
        {
            if(version_compare(PHP_VERSION, '5.4.0', '>='))
            {
                return http_build_query(
                    $this->arr,
                    null,
                    $this->separator,
                    is_null($this->rfc) ? PHP_QUERY_RFC1738 : $this->rfc
                );
            }
            else
            {
                return http_build_query( $this->arr, null, $this->separator);
            }
        }
        else
        {
            return '';
        }
    }
}
