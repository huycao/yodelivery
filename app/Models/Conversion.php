<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Input, Cookie, DB;
use RedisHelper;

class Conversion extends Eloquent{
    const RESPONSE_TYPE_CONVERSION_NOT_FOUND          = 'conversion is not found';
    const RESPONSE_TYPE_CONVERSION_NOT_INVALID        = 'conversion is invalid';
    const RESPONSE_TYPE_CONVERSION_SUCCESS            = 'success';
    const RESPONSE_TYPE_CONVERSION_ERROR              = 'error';
    
    function getConversion($conversionID, $renewCache = false) {
        $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', '6379'));
	    $cacheKey = "Conversion";
	    $cacheField = $conversionID;
		$retval = $redis->hGet($cacheKey, $cacheField);
		if(Input::get('cleared') || $renewCache){
		    $redis->hDel($cacheKey, $cacheField);
			$retval = 0;
		}
		if(!$retval){
			$retval = DB::table('conversion')
		                    ->join('campaign', 'conversion.campaign_id', '=', 'campaign.id')
			                ->where('conversion.id', $conversionID)
			                ->where('conversion.status', 1)
			                ->select('conversion.id', 'conversion.name', 'conversion.campaign_id',
			                    'conversion.param', 'conversion.source', 'campaign.name')
			                ->first();
            if ($retval) {
                $retval->param = json_decode($retval->param);
            }
			$redis->hSet($cacheKey, $cacheField, $retval);
		}
		return $retval;
	}
}