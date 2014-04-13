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
    protected $credential = null;



    public function __get($name)
    {
        if( in_array( $name, self::$arr_parts))
        {
            $method = '_' . $name;
            return $this->$method();
        }
    }


    public function __set($name, $value)
    {
        if(in_array($name, array('scheme', 'host', 'user', 'pass', 'anchor', 'fragment')))
        {
            $method = '_' . $name;
            $this->$method($value);
        }

        if($name == 'credential')
        {
            $this->credential = new Credential($value);
        }

        if($name == 'port')
        {
            $this->value->port = new Port($value);
        }

        if($name == 'path')
        {
            $this->value->path = new Path($value);
        }

        if($name == 'query')
        {
            $this->value->query = new Query($value);
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

            if($k == 'path')
            {
                $this->value->path = new Path($this->value->path);
            }

            if($k == 'port')
            {
                $this->value->port = new Port($this->value->port);
            }

            if($k == 'query')
            {
                $this->value->query = new Query($this->value->query);
            }
        }
        
        if(!$this->credential)
        {
            $this->credential = new Credential();
        }

        $this->credential->user = $this->value->user;
        $this->credential->pass = $this->value->pass;
    }


    protected function _scheme($str = null)
    {
        if(is_string($str))
        {
            $str = preg_replace('@.//$@' , '', $str);

            if(strlen($str) && preg_match('/^[a-z]{1}[a-z0-9\+\.\-]+$/i', $str))
            {
                $this->value->scheme = $str;
            }
            else
            {
                throw new \InvalidArgumentException('Invalid scheme name!');
            }
        }
        return $this->value->scheme ? strtolower($this->value->scheme) : null;
    }
    
    protected function _host($str = null)
    {
        if(is_string($str))
        {
            //TODO: urlencode ? http://be2.php.net/manual/en/function.rawurlencode.php#26869
            //TODO: 255 max with separator! Cf. http://stackoverflow.com/questions/106179/regular-expression-to-match-hostname-or-ip-address
            $str = trim($str);
            $ip = "/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/";
            $name = "/^([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])(\.([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9]))*$/";
            if(strlen($str) > 0 && strlen($str) <= 255 && (preg_match($ip, $str) || preg_match($name, $str)))
            {
                $this->value->host = $str;
            }
            else
            {
                throw new \InvalidArgumentException('Invalid hostname provided');
            }
        }

        return $this->value->host ? $this->value->host : null;
    }
    
    protected function _port($num = null)
    {
        return $this->value->port;
    }
    
    protected function _user($str = null)
    {
        if($str)
        {
            $this->credential->user = $str;
        }

        return $this->credential->user;
    }
    
    protected function _pass($str = null)
    {
        if($str)
        {
            $this->credential->pass = $str;
        }

        return $this->credential->pass;
    }


    public function user($str)
    {
        $this->_user($str);
        return $this;
    }

    public function pass($str)
    {
        $this->_pass($str);
        return $this;
    }
    
    protected function _path()
    {
        return $this->value->path;
    }
    
    public function path($path)
    {
        $p = new Path($path);

        $this->value->path = $this->value->path->merge($p);

        return $this;
    }

    protected function _query()
    {
        return $this->value->query;
    }
    
    protected function _fragment($str = null)
    {
        if($str)
        {
            $this->value->fragment = (string) $str;
        }

        return $this->value->fragment ? $this->value->fragment : null;
    }

    protected function _anchor($str = null)
    {
        return $this->_fragment($str);
    }




    protected function _credential()
    {
        return $this->credential;
    }




    protected function _build()
    {
        $arr = array();
        
        if($this->_scheme())
        {
            $arr[] = $this->_scheme() . '://';
        }

        if(!$this->_credential()->isVoid())
        {
            $arr[] = $this->_credential().'@';
        }

        $arr[] = $this->_host();
        
        if(!$this->_port()->isVoid())
        {
            $arr[] = ':' . $this->_port();
        }

        $arr[] = $this->_path();

        if(!$this->_query()->isVoid())
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
        $this->value->str = $this->_build();
        return $this->value->str;
    }
}
