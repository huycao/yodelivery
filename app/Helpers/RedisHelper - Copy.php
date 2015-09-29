<?php
use PSRedis\Client as Client;
class RedisHelper {
    private static $connection = '';

    public static function connection(){
        if(empty(self::$connection)){
            $parameters = ["tcp://" . env('REDIS_HOST_CLUSTER', '127.0.0.1')];
            $options    = ['cluster' => 'redis'];
            self::$connection = new Predis\Client($parameters, $options);
        }
        return self::$connection;
    }

    public static function flush(){
        return self::connection()->flush();
    }
    public static function set($key, $objValue, $ex=0) {
        if ($ex > 0) {
            return self::connection()->setex($key, $ex*60, json_encode($objValue));
        } else {
            return self::connection()->set($key, json_encode($objValue));
        }
    }

    public static function get($key) {
        return json_decode(self::connection()->get($key));
    }

    public static function mget($key) {
        $keys = self::connection()->keys($key);
        if ($keys) {
            $rs = self::connection()->mget($keys);
            foreach ($rs as $key=>$val) {
                $rs[$key] = json_decode($val);
            }
            return $rs;
        } else {
            return NULL;
        }
    }

    public static function del($key) {
        $keys = self::connection()->keys($key);
        if (!empty($keys)) {
            return self::connection()->del($keys);
        }
    }
    
    public static function expire($key, $time) {
        return self::connection()->expire($key, $time);
    }

    public static function exist($key) {
        return self::connection()->exists($key);
    }
    
    public static function increment($key) {
        return self::connection()->incr($key);
    }
    
    public static function hSet($key, $field, $value) {
        return self::connection()->set($key, $field, $value);
    }

    public static function hGet($key, $field) {
        return self::connection()->hget($key);
    }

    public static function hGetAll($key) {
        return self::connection()->hgetall($key);
    }

    public static function hDel($key, $fields = '') {
        if ($fields == '') {
            $fields = self::connection()->hkeys($key);
        }
        if (!empty($keys)) {
            return self::connection()->hdel($key, $fields);
        }
    }
    
    public static function hExist($key, $field) {
        return self::connection()->hexists($key, $field);
    }
    
    public static function hIncrement($key, $field, $increment = 1) {
        return self::connection()->hincrby($key, $field, $increment);
    }

    public static function zadd($key, $score, $value){
        return self::connection()->zadd($key, $score, $value);
    }

    public static function zcount($key, $fromScore, $toScore){
        return self::connection()->zcount($key, $fromScore, $toScore);
    }
}
