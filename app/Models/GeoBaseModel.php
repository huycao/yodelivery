<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use RedisHelper;

class GeoBaseModel extends Eloquent {

    /**
     *     Table name of model used
     *     @var string
     */
    protected $table = 'geo';

    public $timestamps = false;

    
    /**
     * 
     * Get country, region
     * @param $ip
     */
    public static function getGeoByIp($ip) {
        $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_1', '6379'), false);
        $long_ip = sprintf("%u", ip2long($ip));
        $cacheKey = "geo_" . $long_ip;
        
        return $redis->get($cacheKey);
    }
}