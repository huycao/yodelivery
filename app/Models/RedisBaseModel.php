<?php
namespace App\Models;
use Predis\Client as Client;

class RedisBaseModel {
    public $connection;
    
    public function __construct($host, $port, $cluster = true) {
        if ($cluster) {
            $parameters = ["tcp://" . env('REDIS_HOST', '127.0.0.1') . ':' . env('REDIS_PORT', '6379') ];
            $options    = ['cluster' => 'redis'];
            $this->connection = new Client($parameters, $options);
        } else {
            $this->connection = new Client([
                'scheme' => 'tcp',
                'host'   => $host,
                'port'   => $port
            ]);
        }
    }
    
    public function set($key, $objValue, $ex=0) {
        if ($ex > 0) {
            return $this->connection->setex($key, $ex*60, json_encode($objValue));
        } else {
            return $this->connection->set($key, json_encode($objValue));
        }
    }
    
    public function get($key) {
        return json_decode($this->connection->get($key));
    }

    public function mget($key) {
        $keys = $this->connection->keys($key);
        if ($keys) {
            $rs = $this->connection->mget($keys);
            foreach ($rs as $key=>$val) {
                $rs[$key] = json_decode($val);
            }
            return $rs;
        } else {
            return NULL;
        }
    }

    public function del($key) {
        $keys = $this->connection->keys($key);
        if (!empty($keys)) {
            return $this->connection->del($keys);
        }
    }
    
    public function expire($key, $time) {
        return $this->connection->expire($key, $time);
    }

    public function exist($key) {
        return $this->connection->exists($key);
    }
    
    public function increment($key) {
        return $this->connection->incr($key);
    }
    
    public function hSet($key, $field, $value) {
        if (is_array($value) || is_Object($value)) {
            $value = json_encode($value);
        }
        return $this->connection->hset($key, $field, $value);
    }
    
    public function hMset($key, $arr = array()) {
        return $this->connection->hmset($key, $arr);
    }

    public function hGet($key, $field, $parse = true) {
        $rs = $this->connection->hget($key, $field);
        if ($parse) {
            return json_decode($rs);
        } else {
            return $rs;
        }
    }
    
    public function hMget($key, $field = array(), $parse = true) {
        $rs = $this->connection->hmget($key, $field);
        if ($parse) {
            $arrRs = array();
            foreach ($rs as $key=>$val) {
                if (!empty($val)) {
                    $arrRs[$key] = json_decode($val);
                }
            }
            return $arrRs;
        } else {
            return $rs;
        }
    }

    public function hGetAll($key, $parse = true) {
        $rs = $this->connection->hgetall($key);
        if ($parse) {
            $arrRs = array();
            foreach ($rs as $key=>$val) {
                $arrRs[$key] = json_decode($val);
            }
            return $arrRs;
        } else {
            return $rs;
        }
    }

    public function hDel($key, $fields) {
        return $this->connection->hdel($key, $fields);
    }
    
    public function hExist($key, $field) {
        return $this->connection->hexists($key, $field);
    }
    
    public function hIncrement($key, $field, $increment = 1) {
        return $this->connection->hincrby($key, $field, $increment);
    }

    public function zadd($key, $score, $value){
        return $this->connection->zadd($key, $score, $value);
    }

    public function zcount($key, $fromScore, $toScore){
        return $this->connection->zcount($key, $fromScore, $toScore);
    }
}
