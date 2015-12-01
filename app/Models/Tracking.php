<?php namespace App\Models;

use Jenssegers\Mongodb\Model as Moloquent;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use DeviceDetector\DeviceDetector;
use Illuminate\Support\Facades\Config;
use Piwik\Common;
use Cookie;
use RedisHelper;

class Tracking extends Moloquent{
	const ENCRYPT_KEY = "WX77h2pVRTTEPEP5halkbrw9NIBvIKkHT";
	protected $table = 'trackings_2015_07';
	protected $connection = 'mongodb';


    public function __construct(){
        $this->table = 'trackings_' . date("Y_m");
    }

	public function getDates()
	{
	    return [];
	}

	public $clientInfo = array();

	public function checkPreProcess($requestType, $hostReferer, $zoneID){
		$retval = '';
		if(empty($zoneID)){
			$retval = Delivery::RESPONSE_TYPE_INVALID;
		}
		elseif( empty($hostReferer) && \Input::has('ec') && \Input::get('ec')  && !isLocal()){
			$retval = Delivery::RESPONSE_TYPE_EMPTY_REFERRER;
		}
		// elseif( !isLocal()){
		// 	$isBlocked = $this->isBlockedIp($zoneID);
		// 	if($isBlocked || ($this->countLatestRequest($zoneID,5) > Delivery::ANTI_CHEAT_MAX_REQUEST_PER_5MIN) ){

		// 		$retval = Delivery::RESPONSE_TYPE_ANTI_MANY_REQUEST;
		// 		if(!$isBlocked){
		// 			$this->setBlockIp($zoneID);
		// 		}
		// 	}
		// }
		return $retval;
	}

	public function setBlockIp($zoneID){
		$ip = getIP();
		$cacheKey = "BlockIP_{$zoneID}_{$ip}";
		RedisHelper::hSet($cacheKey, 1, true);
		return RedisHelper::expire($cacheKey, CACHE_2D);
	}

	public function isBlockedIp($zoneID){
		$ip = getIP();
		$cacheKey = "BlockIP_{$zoneID}_{$ip}";
		if(RedisHelper::hExist($cacheKey, 1)){
			return true;
		}
		return false;
	}

	public function logPreProcess($requestType, $inputData = array()){
		// if($requestType != Delivery::REQUEST_TYPE_TRACKING_BEACON){
		// 	//tăng bộ đếm số lần request từ IP client
		// 	$this->incLatestRequest(\Input::get('zid', 0));
		// }
		// $clientInfo = $this->getClientInfo();
		// if($clientInfo){
		// 	if( isset($clientInfo['os']['name']) ){
		// 		$this->os               = $clientInfo['os']['name'];
		// 	}
		// 	if( isset($clientInfo['client']['name']) ){
		// 		$this->browser          = $clientInfo['client']['name'];
		// 	}
		// 	if( isset($clientInfo['client']['browser_language']) ){
		// 		$this->browser_language = $clientInfo['client']['browser_language'];
		// 	}
		// }

		// if( isset($inputData['ovr']) ){
		// 	$this->ovr = $inputData['ovr'];
		// }

		// if($requestType == Delivery::REQUEST_TYPE_TRACKING_BEACON){
		// 	//tracking beacon
		// 	$this->response_type = Delivery::RESPONSE_TYPE_TRACKING_BEACON_SUCCESS;
		// }
		// else{
		// 	$this->response_type = Delivery::RESPONSE_TYPE_LOG_PREPROCESS_SUCCESS;
		// }
		// $this->status               = 0;
		// $this->user_agent           = $_SERVER['HTTP_USER_AGENT'];
		// $this->visitor_ip           = getClientIp();
		// $this->referer              = $this->getRequestReferer();
		// $this->request_type         = $requestType;
		// $this->hour                 = date('G');
		// $this->date                 = date('Y-m-d');
		// $this->event                = !empty($inputData['evt']) ? strtolower($inputData['evt']) : '';
		// $this->query                = $_SERVER['QUERY_STRING'];
		// $this->visitor_id           = $this->getVisitorId();
		// $this->flight_id            = \Input::get('fid', 0);
		// $this->ad_format_id         = 0;
		// $this->ad_id                = \Input::get('aid', 0);
		// $this->campaign_id          = 0;
		// $this->publisher_ad_zone_id = \Input::get('zid', 0);
		// $this->flight_website_id    = \Input::get('fpid', 0);
		// $this->website_id           = \Input::get('wid', 0);
		// $this->unique_impression    = 0;
		
		// if($this->save()){
		// 	return $this;
		// }
		return false;
	}


	/**
	 * kết thúc request, update status
	 */
	public function logAfterProcess($responseType, $expandFields = array()){
		//remember visitor
		// $this->setVisitorId($this->visitor_id);

		// if($expandFields && is_array($expandFields)){
		// 	foreach ($expandFields as $key => $val) {
		// 		$this->{$key}	= is_numeric($val) ? intval($val) : $val;
		// 	}
		// }
		// $this->response_type = $responseType;
		// $this->status = 1;
		// if( $this->save() ){
		// 	return $this;
		// }
		return FALSE;
	}

	public function getRequestReferer(){
        if($ref = \Input::get('ref')){
            return getWebDomain($ref);
        }
		return !empty($_SERVER['HTTP_REFERER']) ? getWebDomain($_SERVER['HTTP_REFERER']) : '';
	}

	/**
	 * số lượng request từ 1 ip trong 1 khoảng thời gian
	 */
	public function countLatestRequest($zoneID, $minute = 1, $ip = ''){
		// $ip = $ip ? $ip : getClientIp();
		// $now = time();
		// $from = strtotime("-{$minute} " . $minute > 1 ? "minutes" : "minute");
		// $cacheKey = "LatestRequest_{$zoneID}_{$ip}";
		// return RedisHelper::zcount($cacheKey, $from, $now);
	}

	/**
	 *  tăng số lượng request từ 1 ip trong 1 khoảng thời gian
	 */
	public function incLatestRequest($zoneID , $ip = ''){
		// $ip = $ip ? $ip : getClientIp();
		// $cacheKey = "LatestRequest_{$zoneID}_{$ip}";
		// RedisHelper::zadd($cacheKey, time(), microtime());
		// return RedisHelper::expire($cacheKey, strtotime("+10 minutes"));
	}

	/**
	 * detect request from BOT via user agent
	 */

	public function isBot($userAgent = ''){
		$userAgent = $userAgent ? $userAgent : $_SERVER['HTTP_USER_AGENT'];
		DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
        $dd = new DeviceDetector($userAgent);

        // OPTIONAL: If called, getBot() will only return true if a bot was detected  (speeds up detection a bit)
        $dd->discardBotInformation();

        $dd->parse();

        return $dd->isBot();
	}
	/**
	 * Information about client requesting
	 * cache base on  user agent + client ip
	 */
	public function getClientInfo($userAgent = ''){
		// if($this->clientInfo){
		// 	return $this->clientInfo;
		// }
		// $userAgent = $userAgent ? $userAgent : @$_SERVER['HTTP_USER_AGENT'];
		// $UAHash    = $this->makeUserAgentHash($userAgent);
		// $cacheKey  = "ClientInfo_{$UAHash}";
		// $cacheField = "1";
		// $fromCache = RedisHelper::hGet($cacheKey, $cacheField, false);
		// if(!$fromCache){
		// 	DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
	 //        $dd = new DeviceDetector($userAgent);

	 //        // OPTIONAL: If called, getBot() will only return true if a bot was detected  (speeds up detection a bit)
	 //        $dd->discardBotInformation();

	 //        $dd->parse();

	 //        if ($dd->isBot()) {
	 //          // handle bots,spiders,crawlers,...
	 //          // $clientInfo = $dd->getBot();
	 //        	return false;
	 //        } else {
		// 		$clientInfo['client']           = $dd->getClient(); // holds information about browser, feed reader, media player, ...
		// 		$clientInfo['os']               = $dd->getOs();
		// 		$clientInfo['device']           = $dd->getDevice();
		// 		$clientInfo['brand']            = $dd->getBrand();
		// 		$clientInfo['model']            = $dd->getModel();
	 //        }

	 //        RedisHelper::hSet($cacheKey, $cacheField, json_encode($clientInfo));
	 //        RedisHelper::expire($cacheKey, \Config::get('cache_time.defaultCacheTimeInSeconds'));
		// }
		// else{
		// 	$clientInfo = json_decode($fromCache, 1);
		// }
  //       $this->clientInfo = $clientInfo;

  //       return $clientInfo;
    }


    /**
     * ouput 1x1 transparent gif
     */
    public function outputTransparentGif(){
        header('Content-Type: image/gif');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		echo base64_decode("R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==");
		exit();
    }

    public function setVisitorId($visitorId = ''){
		if(!$visitorId){
    		$visitorId = $this->makeVisitorId();
    		$this->visitor_id = $visitorId;
    	}
        
        if(!isset($_COOKIE['uuid'])){
            setcookie('uuid', $visitorId, time()+(86400*365), '/', getWebDomain(DOMAIN_COOKIE));            
        }
        return $visitorId;
    }

    public function getVisitorId(){
    	if(!isset($_COOKIE['uuid'])){
    		return $this->setVisitorId();
    	} else {
            return $_COOKIE['uuid'];
        }
    }
    /**
     * make unique visitor id base on client IP and client Browser User Agent
     */
    public function makeVisitorId($userAgent = '', $clientIp = ''){	
		/*$userAgent = $userAgent ? $userAgent : $_SERVER['HTTP_USER_AGENT'];
		$clientIp  = $clientIp ? $clientIp : getClientIp();
    	return md5( $userAgent . $clientIp);*/
        return makeUuid();
    }

    public function makeUserAgentHash($userAgent = ''){
    	$userAgent = $userAgent ? $userAgent : $_SERVER['HTTP_USER_AGENT'];
    	return md5($userAgent);
    }


   
    public function isUniqueImpression($flight_website_id, $date = ''){
    	$visitorId = $this->getVisitorId();
    	$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', '6379'));
    	$cacheKey = "UniqueImpression_{$flight_website_id}_{$date}_{$visitorId}";
    	$cacheField = "1";
    	$retval = $redis->hExist($cacheKey, $cacheField);
    	if(!$retval){
    		$redis->hSet($cacheKey, $cacheField, true);
    		$redis->expire($cacheKey, CACHE_1Y);
    		return true;
    	}
    	return false;
    }
    public function isUniqueClick($flight_website_id, $date = ''){
    	$visitorId = $this->getVisitorId();
    	$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', '6379'));
    	$cacheKey = "UniqueClick_{$flight_website_id}_{$date}_{$visitorId}";
    	$cacheField = "1";
    	$retval = $redis->hExist($cacheKey, $cacheField);
    	if(!$retval){
    		$redis->hSet($cacheKey, $cacheField, true);
    		$redis->expire($cacheKey, CACHE_1Y);
    		return true;
    	}
    	return false;
    }

    public function isUniqueVisitor() {
        if (isset($_COOKIE['uuid'])) {
            return true;
        }
        return false;
    }

    /*public function incFreCap($flightId){
    	$visitorId = $this->getVisitorId();
    	$today = date('Y_m_d');
    	$cacheKey = "Tracking:FrequencyCap:{$flightId}:{$visitorId}:{$today}";
    	$todayFreCap = $this->getTodayFreCap($flightId);
    	if(!$todayFreCap){
    		return Cache::put($cacheKey, 1, CACHE_1D);
    	}
    	else{
    		return Cache::increment($cacheKey);
    	}

    }*/
    public function incFreCap($flight){
    	$visitorId = $this->getVisitorId();
    	$today = date('Y_m_d');
    	$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', '6379'));
    	$cacheKey = "Tracking:FrequencyCap_{$flight->id}_{$visitorId}_{$flight->event}";
    	$cacheField = $today;
    	$cacheKeyTime = "Tracking:TimeFrequencyCap_{$flight->id}_{$visitorId}_{$flight->event}";
    	$redis->hSet($cacheKeyTime, $cacheField, 1);
    	
    	$todayFreCap = $this->getTodayFreCap($flight);
    	if(!$todayFreCap){
    		$redis->hSet($cacheKey, $cacheField, 1);
    		return $redis->expire($cacheKey, CACHE_1D);
    	}
    	else{
    		return $redis->hIncrement($cacheKey, $cacheField);
    	}

    }

    public function getTodayFreCap($flight){
    	$visitorId = $this->getVisitorId();
    	$today = date('Y_m_d');
    	$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', '6379'));
    	$cacheKey = "Tracking:FrequencyCap_{$flight->id}_{$visitorId}_{$flight->event}";
    	$cacheField = $today;
    	$fromCache = $redis->hGet($cacheKey, $cacheField, false);
    	if($fromCache){
    		return $fromCache;
    	}
    	return 0;
    }
    
    public function getTimeFreCap($flight){
    	$visitorId = $this->getVisitorId();
    	$today = date('Y_m_d');
    	$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', '6379'));
    	$cacheKey = "Tracking:TimeFrequencyCap_{$flight->id}_{$visitorId}_{$flight->event}";
    	$cacheField = $today;
    	$fromCache = $redis->hGet($cacheKey, $cacheField, false);
    	if($fromCache){
    		return $fromCache;
    	}
    	return 0;
    }
    
    public function setTimeFreCap($flight, $expire) {
        $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', '6379'));
        $visitorId = $this->getVisitorId();
    	$cacheKey = "Tracking:FrequencyCap_{$flight->id}_{$visitorId}_{$flight->event}";
    	
    	$redis->expire($cacheKey, $expire * 60);
    }

    public function previousTrackingEvent($event){
    	switch ($event) {
    		case 'start':
    			$retval = 'beforeStart';
    			break;
    		case 'impression':
    			$retval = 'beforeImpression';
    			break;
    		case 'click': 
    			$retval = 'ads_success';
    			break;
			case 'pause': 
    			$retval = 'start';
    			break;
    		default:
    			$events = array(
	    			'impression',
	    			'firstquartile',
		    		'midpoint',
		    		'thirdquartile',
		    		'complete'
	    		);
	    		$searchKey = array_search($event, $events);
	    		if($searchKey !== FALSE && $searchKey != 0){
	    			$retval = $events[$searchKey - 1];
	    		}
	    		else{
	    			$retval = false;
	    		}
    			break;
    	}
    	return $retval;
    }

    public function isValidTrackingBeacon($checksum, $event){
        return true;
  //       $eventsCheck = array(
  //   		'start',
  //   		'click',
  //   		'impression',
  //   		'firstquartile',
  //   		'midpoint',
  //   		'thirdquartile',
  //   		'complete',
  //           'pause'
  //   	);
  //   	if(in_array($event, $eventsCheck)){
  //   		$previousEvent = $this->previousTrackingEvent($event);
  //   		$cacheKey = "Checksum_{$checksum}_{$previousEvent}";
  //   		$cacheField = 1;
  //   		$retval = RedisHelper::hGet($cacheKey, $cacheField, false);
  //   		if($retval){
  //   			//remove key cache -> chỉ chấp nhận request tracking đầu tiên
  //   			RedisHelper::hDel($cacheKey, $cacheField);
  //   		}
  //   		return $retval;
  //   	}

		// return false;
    }

    public function setChecksumTrackingEvent($checksum, $event){
  //   	if($event == Delivery::RESPONSE_TYPE_ADS_SUCCESS){
  //   		//custom đối với event ads_success do 2 event impression và start thứ tự call random theo từng request . P/S: fucking jwplayer
  //   		RedisHelper::hSet("Checksum_{$checksum}_beforeImpression", 1, true);
  //   		RedisHelper::expire("Checksum_{$checksum}_beforeImpression", 600);
  //   		RedisHelper::hSet("Checksum_{$checksum}_beforeStart", 1, true);
  //   		RedisHelper::expire("Checksum_{$checksum}_beforeStart", 600);
  //   	}

		// $cacheKey = "Checksum_{$checksum}_{$event}";	
  //   	RedisHelper::hSet($cacheKey, 1, true);
    	
  //   	return RedisHelper::expire($cacheKey, 600);
    }

    public function makeChecksumHash($flightWebsiteID){
    	// $visitorId = $this->getVisitorId();
    	// return md5(self::ENCRYPT_KEY . $flightWebsiteID . $visitorId . microtime());
    }

    public function rememberFrequencyCap($fwid, $expire){
	    $cookieName = md5("FrequencyCap:$fwid");
    	Cookie::make($cookieName, time(), $expire);
    }
    /**
     * 
     */
    public function calculateRetargetpoint($listCampaignId){
    	$retval = 0;
    	$listCampaignId = json_decode($listCampaignId);
    	if(!empty($listCampaignId)){
	    	$eventsFocus = [
	    		'impression'	=>	1,
	    		'complete'		=>  2,
	    		'click'			=>	3 // click tính điểm cao nhất
	    	];
	    	foreach ($listCampaignId as $campaign) {
	    		foreach ($eventsFocus as $event => $point) {
		    		$cookieName = "Retargeting:{$campaign->id}:{$event}";
		    		$retval += Cookie::get($cookieName) ? $point : 0;
		    	}
	    	}

    	}
    	return $retval;
    }

    public static function getTrackingEventType($costType){
    	if( $costType == 'cpm' || $costType == 'cpc' ){
    		if( $costType == 'cpm' ){
    			return 'impression';
    		}else{
    			return 'click';
    		}
    	}else{
    		return false;
    	}
    }

   	public function updateInventory($flightID, $flightWebsiteID, $event, $overReport = false){
   	    $trackInventory = $overReport ? new TrackingOverInventory : new TrackingInventory;
        try{
        	$trackInventory->incTotalAdZoneInventory($flightID, $flightWebsiteID, $event);
        	$trackInventory = $overReport ? new TrackingOverInventory : new TrackingInventory;
        	$trackInventory->incTotalInventory($flightID, $event);
        	return true;
        }
        catch(Exception $exception){
        	Log::error($exception);
        	throw $exception;
        	return false;
        }
    }


    //create expand event tracking link
    public static function expandTrackingLink($adID, $flightWebsiteID, $adzoneID, $checksum){
    	$params = array(
    		'evt'	=>	'expand',
    		'aid'	=>	$adID,
    		'fpid'	=>	$flightWebsiteID,
    		'zid'	=>	$adzoneID,
    		'rt'	=>	Delivery::REQUEST_TYPE_TRACKING_BEACON,
    		'cs'	=>	$checksum
    	);
    	return self::createTrackingLink($params);
    }

 	//create click event tracking link
    public static function clickTrackingLink($toURL, $adID, $flightWebsiteID, $adzoneID, $checksum){
    	$params = array(
    		'evt'	=>	'click',
    		'aid'	=>	$adID,
    		'fpid'	=>	$flightWebsiteID,
    		'zid'	=>	$adzoneID,
    		'rt'	=>	Delivery::REQUEST_TYPE_TRACKING_BEACON,
    		'cs'	=>	$checksum,
    		'to'	=>	$toURL
    	);
    	return self::createTrackingLink($params);
    }

    //create expand event tracking link
    public static function impressionTrackingLink($adID, $flightWebsiteID, $adzoneID, $checksum){
    	$params = array(
    		'evt'	=>	'impression',
    		'aid'	=>	$adID,
    		'fpid'	=>	$flightWebsiteID,
    		'zid'	=>	$adzoneID,
    		'rt'	=>	Delivery::REQUEST_TYPE_TRACKING_BEACON,
    		'cs'	=>	$checksum
    	);
    	return self::createTrackingLink($params);
    	
    }
    public static function createTrackingLink($params){
    	return TRACKER_URL . 'track?'. http_build_query($params);
    }
}
