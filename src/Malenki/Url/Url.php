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

class Url
{
    protected static $arr_parts = array(
        'scheme',
        'host',
        'port',
        'user',
        'pass',
        'path',
        'query',
        'fragment',
        'anchor',
        'credential'
    );

    protected $value = null;



    public function __get($name)
    {
        if( in_array( $name, self::$arr_parts))
        {
            $method = '_' . $name;
            return $this->$method();
        }
    }



    public function __construct($url)
    {
        $arr_keys = array_slice(self::$arr_parts, 0, 8);

        if(is_string($url))
        {
            $this->value = (object) parse_url($url);
        }
        elseif(is_array($url) || is_object($url))
        {

            if(is_object($url))
            {
                $url = (array) $url;
            }

            foreach($url as $k => $v)
            {
                if(!in_array($k, $arr_keys))
                {
                    unset($url[$k]);
                }
            }

            if(count($url))
            {
                $this->value = (object) $url;
            }
        }

        
        $this->_ensureKeys();
        $this->value->str = $this->_build();
    }


    protected function _ensureKeys()
    {
        $arr_keys = array_slice(self::$arr_parts, 0, 8);

        foreach($arr_keys as $k)
        {
            if(!isset($this->value->$k))
            {
                $this->value->$k = null;
            }
        }
    }


    protected function _scheme()
    {
        return $this->value->scheme ? $this->value->scheme : null;
    }
    
    protected function _host()
    {
        return $this->value->host ? $this->value->host : null;
    }
    
    protected function _port()
    {
        return $this->value->port ? $this->value->port : null;
    }
    
    protected function _user()
    {
        return $this->value->user ? $this->value->user : null;
    }
    
    protected function _pass()
    {
        return $this->value->pass ? $this->value->pass : null;
    }
    
    protected function _path()
    {
        return $this->value->path ? new Path($this->value->path) : null;
    }
    
    protected function _query()
    {
        return $this->value->query ? new Query($this->value->query) : null;
    }
    
    protected function _fragment()
    {
        return $this->value->fragment ? $this->value->fragment : null;
    }

    protected function _anchor()
    {
        return $this->_fragment();
    }




    protected function _credential()
    {
        if($this->_user())
        {
            $out = new \stdClass();
            $out->user = $this->_user();
            $out->pass = $this->_pass();
            $out->str = $out->user . ($out->pass ? ':'. $out->pass : '');

            return $out;
        }

        return null;
    }




    protected function _build()
    {
        $arr = array();
        
        if($this->_scheme())
        {
            $arr[] = $this->_scheme() . '://';
        }

        if($this->_credential())
        {
            $arr[] = $this->_credential()->str.'@';
        }

        $arr[] = $this->_host();
        
        if($this->_port())
        {
            $arr[] = ':' . $this->_port();
        }

        $arr[] = $this->_path();

        if($this->_query())
        {
            $arr[] = '?' . $this->_query();
        }

        if($this->_fragment())
        {
            $arr[] = '#' . $this->_fragment();
        }

        return implode('', $arr);
    }


    public function __toString()
    {
        return $this->value->str;
    }
}
