<?php

namespace SendGrid;

class Bounce extends Api
{
    protected $domain = "http://sendgrid.com/";
    protected $endpoint = "api/bounces.get.json";
    
    private $data = array();
    
    private function getUrlWithGetParams($params = array())
    {
        $params = array_merge($params, array('api_user' => $this->username, 'api_key' => $this->password));
        $paramsString = '';
        foreach ($params as $key => $value) {
            $paramsString .= "&" . rawurlencode($key) . "=" . rawurlencode($value);
        }
    
        return sprintf($this->domain . $this->endpoint . '?' . $paramsString);
    }
    
    /**
     * __construct
     * Create a new Web instance
     */
    public function __construct($username, $password)
    {
        call_user_func_array("parent::__construct", func_get_args());
    }
    
    public function makeRequest($params = array(), $endpoint = "api/bounces.get.json") {
        $this->endpoint = $endpoint;
        if($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $this->getUrlWithGetParams($params));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $out = json_decode(curl_exec($curl), true);
            $err = curl_errno($curl);
            $result = $out;
            curl_close($curl);
            return $result;
        } else {
            throw new \RuntimeException ("No curl installed");
        }
    }
    
    public function count () {
        $result = $this->makeRequest(array(), "bounces.count.json");
        return (int)@$result->count;
    }
    
    public function current () {
        return current($this->data);
    }
    
    public function next () {
        return next($this->data);
    }
    
    public function key () {
        return key($this->data);
    }
    
    public function valid () {
        return (bool)current($this->data);
    }
    
    public function rewind () {
        return reset($this->data);
    }
    
    /**
     * @param offset
     */
    public function offsetExists ($offset) {
        return isset($this->data[$offset]);
    }
    
    /**
     * @param offset
     */
    public function offsetGet ($offset) {
        if(isset($this->data[$offset])) {
            return $this->data[$offset];
        }
        return false;
    }
    
    /**
     * @param offset
     * @param value
     */
    public function offsetSet ($offset, $value) {
        $this->data[$offset] = $value;
    }
    
    /**
     * @param offset
     */
    public function offsetUnset ($offset) {
        if(isset($this->data[$offset])) {
            unset($this->data[$offset]);
        }
    }
}