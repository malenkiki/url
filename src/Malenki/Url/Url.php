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
        $this->value = $url;
    }


    protected function _scheme()
    {
        return parse_url($this->value, PHP_URL_SCHEME);
    }
    
    protected function _host()
    {
        return parse_url($this->value, PHP_URL_HOST);
    }
    
    protected function _port()
    {
        return parse_url($this->value, PHP_URL_PORT);
    }
    
    protected function _user()
    {
        return parse_url($this->value, PHP_URL_USER);
    }
    
    protected function _pass()
    {
        return parse_url($this->value, PHP_URL_PASS);
    }
    
    protected function _path()
    {
        return parse_url($this->value, PHP_URL_PATH);
    }
    
    protected function _query()
    {
        return parse_url($this->value, PHP_URL_QUERY);
    }
    
    protected function _fragment()
    {
        return parse_url($this->value, PHP_URL_FRAGMENT);
    }

    protected function _anchor()
    {
        return $this->_fragment();
    }

    protected function _credential()
    {
        if($this->_user() && $this->_path())
        {
            $out = new \stdClass();
            $out->user = $this->_user();
            $out->pass = $this->_pass();
            $out->str = sprintf('%s:%s', $out->user, $out->pass);

            return $out;
        }

        return null;
    }

    public function __toString()
    {
        return $this->value;
    }
}
