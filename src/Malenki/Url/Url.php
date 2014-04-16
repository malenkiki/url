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

/**
 * Url utility 
 * 
 * @property-read $scheme Get the Scheme object (http, https, ftp…), so, you can have some informations, and you can change it too
 * @property-read $credential Get the credential object, to set/change/avoid, full credential or just username or password
 * @property-read $user Get the credential's user part, it is a shorthand for the credentail's user part call
 * @property-read $pass Get the credential's password part, it is a shorthand for the credentail's pass part call
 * @property-read $host Get the host string part
 * @property-read $port Get the Port object, so you can chage it or avoid it
 * @property-read $path Get the Path object, so you can add branch, remove some of them or even avoid full path
 * @property-read $query Get the Query object, so you can add, change, delete key/value or avoid whole Query content
 * @property-read $fragment Get the Fragment object, so you can edit or avoid it
 * @property-read $anchor Get the same thing as $fragment magic getter, it is an alias
 * @copyright 2014 Michel Petit
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
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



    /**
     * Magic getter call, allowing use of each URL's part by calling its name 
     * with or without some prefix.
     *
     * If called without any prefix, then you get wanted part as object, and 
     * you can use it as string or object and use its own methods.
     *
     * If it is prefixed with `no_` or `disable_`, then given part is avoid.
     *
     * If the prefix is `has_`, then it is true is it is present or false.
     * 
     * @param string $name One name of the available part
     * @access public
     * @return mixed
     */
    public function __get($name)
    {
        // get object of each part
        if( in_array( $name, self::$arr_parts))
        {
            $method = '_' . $name;
            return $this->$method();
        }

        // disable part
        if(preg_match('/^(no_|disable_)/', $name))
        {
            return $this->no(preg_replace('/^(no_|disable_)/', '', $name));
        }

        // test part
        if(preg_match('/^has_/', $name))
        {
            return $this->has(preg_replace('/^has_/', '', $name));
        }

    }


    /**
     * Magic setters to change each URL's part. 
     * 
     * @param string $name URL part name
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function __set($name, $value)
    {
        if(in_array($name, array('scheme', 'host', 'user', 'pass')))
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

        if(in_array($name, array('anchor', 'fragment')))
        {
            $this->value->fragment = new Fragment($value);
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


    /**
     * Instanciate object for each URL part, event void part. 
     * 
     * @access protected
     * @return void
     */
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

            if($k == 'fragment')
            {
                $this->value->fragment = new Fragment($this->value->fragment);
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
    
    protected function _port()
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
    
    protected function _path()
    {
        return $this->value->path;
    }
    protected function _query()
    {
        return $this->value->query;
    }
    
    protected function _fragment()
    {
        return $this->value->fragment;
    }

    protected function _anchor()
    {
        return $this->_fragment();
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

        if(!$this->_fragment()->isVoid())
        {
            $arr[] = '#' . $this->_fragment();
        }

        return implode('', $arr);
    }


    public function no($name)
    {
        $fct = function($name){
            if( in_array( $name, self::$arr_parts))
            {
                if($name == 'credential')
                {
                    $this->credential->clear;
                }
                else
                {
                    if($name == 'anchor')
                    {
                        $name = 'fragment';
                    }
                    $this->value->$name->clear;
                }
            }
        };

        if(is_array($name))
        {
            foreach($name as $n)
            {
                $fct($n);
            }

            return $this;
        }

        $fct($name);

        return $this;
    }



    public function disable($name)
    {
        return $this->no($name);
    }



    public function has($name)
    {
        if( in_array( $name, self::$arr_parts))
        {
            if($name == 'credential')
            {
                return !$this->credential->isVoid();
            }
            else
            {
                if($name == 'anchor')
                {
                    $name = 'fragment';
                }
                return !$this->value->$name->isVoid();
            }
        }
        else
        {
            throw new \InvalidArgumentException(
                sprintf('Part %s does not exist!', $name)
            );
        }
    }

    public function scheme($str)
    {
        $this->_scheme($str);

        return $this;
    }
    

    public function credential($str)
    {
        $this->credential = new Credential($str);

        return $this;
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


    public function host($str)
    {
        $this->_host($str);

        return $this;
    }


    public function port($port)
    {
        $this->value->port->set($port);
        return $this;
    }

    
    public function path($path)
    {
        $p = new Path($path);

        $this->value->path = $this->value->path->merge($p);

        return $this;
    }

    public function query($q)
    {
        $this->value->query = $this->value->query->merge($q);

        return $this;
    }

    public function fragment($str)
    {
        $this->value->fragment->set($str);
        return $this;
    }

    public function anchor($str)
    {
        return $this->fragment($str);
    }

    public function clear($part = null)
    {
        $arr_clearable = array('credential', 'port', 'path', 'query', 'anchor', 'fragment');

        if(is_null($part))
        {
            foreach($arr_clearable as $p)
            {
                $method = '_'.$p;
                $this->$method()->clear();
            }

            return $this;
        }

        if(in_array($part, $arr_clearable))
        {
            $method = '_'.$part;
            $this->$method()->clear();
        }
        else
        {
            throw new \InvalidArgumentException('Bad part name to clear.');
        }

        return $this;
    }


    public function __clone()
    {
        $this->value = clone $this->value;
        $this->value->port = clone $this->value->port;
        $this->value->path = clone $this->value->path;
        $this->value->query = clone $this->value->query;
        $this->value->fragment = clone $this->value->fragment;
        $this->credential = clone $this->credential;
    }


    public function __toString()
    {
        $this->value->str = $this->_build();
        return $this->value->str;
    }
}
