<?php

namespace Akalod;


use Predis;

class Cache
{

    public static $client = null;
    public static $cacheTime = 3000;

    public static function init($host='localhost',$port=6379,$auth=null)
    {

        if (!self::$client) {
            Predis\Autoloader::register(); 
            self::$client = new Predis\Client([
                "scheme" => "tcp",
                "host" => $host,
                "port" => $port
            ]);
            if ($auth) {
                self::$client->auth($auth);
            }
        }
    }
    
    public static function get($name, callable $fail = null)
    {
   
        $r = self::check($name);
        if ($r)
            return $r; 
        if ($fail)
            return $fail();
    }


    public static function check($name)
    {
        $value = self::$client->get($name);
        if (!$value) {
            return false;
        }
        return json_decode($value);
    }

    public static function set($name, $data,  $time = null)
    {
    
        self::$client->set($name, json_encode($data));
        self::$client->expire($name, ($time ? $time : self::$cacheTime));
         
    
    }

    public static function getSet($name, callable $q, $time = null)
    {
        return self::get($name, function () use ($q, $time, $name) {
            $r = $q();
            self::set($name, $r, ($time ? $time : self::$cacheTime));
            return $r;
        });
    }

}