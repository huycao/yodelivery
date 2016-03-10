<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Input, Cookie, DB;
use RedisHelper;
use DeviceDetector\DeviceDetector;
//use MobileDetect;

class Delivery extends Eloquent{
	const TRACKING_CODE_ADID                          = 14; //ad format tracking code ID
	const REQUEST_TYPE_TRACKING_BEACON                = 1;
	const REQUEST_TYPE_AD                             = 2;
	const REQUEST_TYPE_TRACKING_CODE                  = 3;
	const AD_CACHE_TIME                               = 30;
	const FLIGHT_CACHE_TIME                           = 30;
	const RESPONSE_TYPE_FLIGHTDATE_NOT_AVAILABLE      = 'flightdate_not_available';
	const RESPONSE_TYPE_INVALID                       = 'invalid_request';
	const RESPONSE_TYPE_EMPTY_REFERRER                = 'empty_referrer';
	const RESPONSE_TYPE_REFERRER_NOT_MATCH            = 'referrer_not_match';
	const RESPONSE_TYPE_LOG_PREPROCESS_SUCCESS        = 'log_preprocess';
	const RESPONSE_TYPE_TRACKING_BEACON_SUCCESS       = 'tracking_beacon_success';
	const RESPONSE_TYPE_ADS_SUCCESS                   = 'ads_success';
	const RESPONSE_TYPE_GEO_LIMIT                     = 'geo_not_suitable';
	const RESPONSE_TYPE_GENDER_LIMIT                  = 'gender_not_suitable';
	const RESPONSE_TYPE_AGE_LIMIT                     = 'age_not_suitable';
	const RESPONSE_TYPE_AUDIENCE_LIMIT                = 'audience_not_suitable';
	const RESPONSE_TYPE_AD_ZONE_INVENTORY_LIMIT       = 'ad_zone_inventory_limited';
	const RESPONSE_TYPE_AD_ZONE_DAILY_INVENTORY_LIMIT = 'ad_zone_daily_inventory_limited';
	const RESPONSE_TYPE_INVENTORY_LIMIT               = 'inventory_limited';
	const RESPONSE_TYPE_DAILY_INVENTORY_LIMIT         = 'daily_inventory_limited';
	const RESPONSE_TYPE_CHANNEL_LIMIT                 = 'channel_not_suitable';
	const RESPONSE_TYPE_NOT_AVAILABLE                 = 'no_ads_available';
	const RESPONSE_TYPE_ANTI_MANY_REQUEST             = 'too_many_request';
	const RESPONSE_TYPE_EMPTY_VISITOR_ID              = 'empty_visitor_id';
	const RESPONSE_TYPE_FREQUENCY_CAPPED              = 'frequency_capped';
	const RESPONSE_TYPE_FLIGHT_VALUE_ADDED            = 'flight_does_not_apply_value_added';
	const RESPONSE_TYPE_FLIGHT_WEBSITE_VALUE_ADDED    = 'flight_website_does_not_apply_value_added';
	const DELIVERY_STATUS_OK                          = 'ready_to_deliver';
	const RESPONSE_TYPE_CHECKSUM_ERROR                = 'checksum_error';
	const DELIVERY_STATUS_OVER_REPORT                 = 'delivery_over_report';
	const PLATFORM_TYPE_INVALID                       = 'invalid_platform';
	const TAG_TYPE_INVALID                            = 'invalid_tag';
	const ANTI_CHEAT_MAX_REQUEST_PER_1MIN             = 500;
	const ANTI_CHEAT_MAX_REQUEST_PER_5MIN             = 2000;

	
	/**
	 * kiểm tra status của ad và client trước khi trả về browser
	 */
	public function deliveryAd($ad, $flightWebsite, $flight, $dateRange){
		//kiểm tra limit inventory
		$deliveredAdStatus = $this->deliveredAdStatus($ad->id, $flightWebsite, $flight, $dateRange);

		return $deliveredAdStatus;

	}
	/**
	 * check client province in ad's geo target
	 * input array province number
	 * return boolean
	 */
	public function checkGeo($listCountries, $listProvinces){
		if(empty($listCountries) || isLocal()){
			return true;
		}

		$retval = false;
		$ip = getClientIp(); //live
		
        $geoip = GeoBaseModel::getGeoByIp($ip);
        pr($geoip);
	    if($geoip){
	        if (empty($listProvinces)) {
	            $retval = in_array($geoip->country_code, $listCountries);
	        } else {
    	        $province = "{$geoip->country_code}:{$geoip->region}";
                $retval = in_array($province, $listProvinces);
	        }
        }
        
        return $retval;
	}

	/**
	 * check client's age
	 * input array client's age range
	 * return boolean
	 */
	public function checkAge($listAge, $age = 0){
		if(empty($listAge)){
			return true; 
		}
		return in_array($age, $listAge) ? true : false;
	}

	/**
	 * check client's age
	 * input array client's age range
	 * return boolean
	 */
	public function checkGender($targetGender, $clientGender){
			if(empty($targetGender)){
			return true;
		}
		else{
			if($clientGender == $targetGender){
				return true;
			}
		}
		return false;
			
	}

	/**
	 * check client's viewed an ad not greater than frequency cap
	 * input array client's age range
	 * return boolean
	 */
	/*public function checkFrequencyCap($flightId, $frequencyCap){
		$trackingModel = new Tracking;
		$todayCapped = $trackingModel->getTodayFreCap($flightId);
		if($todayCapped >= $frequencyCap){
			return false;
		}
		else{
			return true;
		}
	}*/
    public function checkFrequencyCap($flight, $flightDates, $flightWebsiteId){
        $trackingModel = new Tracking();
    	$todayCapped = $trackingModel->getTodayFreCap($flight);
		$timeCapped = $trackingModel->getTimeFreCap($flight);
		$now = strtotime(date('Y-m-d H:i'));
		if (!empty($flightDates)) {
    		foreach ($flightDates as $date) {
    			if(strtotime($date->start) <= $now && strtotime($date->end) >= $now){
        			if($date->frequency_cap > 0 && ($todayCapped >= $date->frequency_cap || $timeCapped > 0)){
            			return false;
            		}
    			}
    		}
		}
		return true;
	}

	/**
	 *  check inventory limit of requested flight
	 */
	public function checkInventory($flight, $flightWebsite, $event, $dateRange){
	    if (isLocal()) {
		    return true;
		}
		$RawTrackingSummarry    = new RawTrackingSummary;
		
		$rate                   = $flight->cost_type == 'cpm' ? 1000 : 1;
		$flightWebsiteInventory = !empty($flightWebsite) ? $flightWebsite->total_inventory * $rate : 0;
		$flightInventory        = $flight->total_inventory * $rate;
		if( $flightWebsiteInventory > 0 ){
			// tổng số inventory của flightWebsite (all time)
			$totalAdZoneInventory = $RawTrackingSummarry->getTotalAdZoneInventory($flight->id, $flightWebsite->id, $event);
			if($totalAdZoneInventory >= $flightWebsiteInventory){
				return self::RESPONSE_TYPE_AD_ZONE_INVENTORY_LIMIT;
			}

		}

		// tổng số inventory của flight (all time)
        $flightInventoryAllTime = $RawTrackingSummarry->getTotalInventory($flight->id, $event);

        if($flightInventoryAllTime >= $flightInventory){
			return self::RESPONSE_TYPE_INVENTORY_LIMIT;
		}

		if( $flight->is_fix_inventory == 1 && $flight->day){
			$flightInventoryPerDay     = ceil($flightInventory / ($flight->day) );
			$flightInventoryThisTime   = 0;
			$inventory                 = FlightBaseModel::getCurrentDayRun($dateRange, $flightInventoryPerDay);
			
			if($inventory){
				$flightInventoryThisTime   = ($inventory['inventory_current']) * $rate;
				$flightInventoryInDay      = $RawTrackingSummarry->getTotalInventoryInDay($flight->id, $event);
				
				$flightInventoryAllTimeExp = ($inventory['inventory_exp'] + $inventory['inventory_current']) * $rate;
	      
				if(($flightInventoryAllTime >= $flightInventoryAllTimeExp) && ($flightInventoryInDay >= $flightInventoryThisTime)){
					return self::RESPONSE_TYPE_AD_ZONE_DAILY_INVENTORY_LIMIT;
				}
			}
			// $inventory == false -> unlimit
			
		}
		return true;
	}
	/**
	 *  check over inventory limit of requested flight
	 */
	public function checkOverInventory($flight, $flightWebsite, $event){
		$RawTrackingSummarry = new RawTrackingSummary;
		
		$rate = $flight->cost_type == 'cpm' ? 1000 : 1;

		$flightWebsiteInventoryOvr = $flightWebsite->value_added * $rate;
		$flightInventoryOvr = $flight->value_added * $rate;
        $flightRateOvr = 0;
        
        //Check flight co setup over report
        if ($flightInventoryOvr <= 0) {
            return self::RESPONSE_TYPE_FLIGHT_VALUE_ADDED;
        }
        
        //Check flight webiste co setup over report
	    if ($flightWebsiteInventoryOvr < 0) {
            return self::RESPONSE_TYPE_FLIGHT_WEBSITE_VALUE_ADDED;
        }
        
	    $flightRateOvr = $this->changePercentage($flight->value_added, $flight->total_inventory);
		// tổng số over inventory của flight (all time)
		$flightInventoryAllTimeOvr = $RawTrackingSummarry->getTotalInventory($flight->id, $event, true);
	    $flightInventoryInDayOvr = $RawTrackingSummarry->getTotalInventoryInDay($flight->id, $event, true);
		$flightInventoryInDay = $RawTrackingSummarry->getTotalInventoryInDay($flight->id, $event);

		$flightRateInDayOvr = $this->changePercentage($flightInventoryInDayOvr, $flightInventoryInDay);

		if($flightInventoryInDay <= 0 && $flightRateInDayOvr >= $flightRateOvr || $flightInventoryAllTimeOvr >= $flightInventoryOvr){
			return self::RESPONSE_TYPE_INVENTORY_LIMIT;
		}
		
	    if ($flightWebsiteInventoryOvr == 0) {
	        $fwRateOvr = $flightRateOvr;
	    } else {
	        $fwRateOvr = $this->changePercentage($flightWebsite->value_added, $flightWebsite->total_inventory);
	    }
	    
	    if ($flightWebsiteInventoryOvr > $flightInventoryOvr || $flightWebsiteInventoryOvr == 0) {
	        $flightWebsiteInventoryOvr = $flightInventoryOvr;
	    }
		
		$totalAdZoneInventoryAllTimeOvr = $RawTrackingSummarry->getTotalAdZoneInventory($flight->id, $flightWebsite->id, $event, true);
	    $totalAdZoneInventoryInDayOvr = $RawTrackingSummarry->getTotalAdZoneInventoryInDay($flight->id, $flightWebsite->id, $event, true);
		$totalAdZoneInventoryInDay = $RawTrackingSummarry->getTotalAdZoneInventoryInDay($flight->id, $flightWebsite->id, $event);

		$fwRateInDayOvr = $this->changePercentage($totalAdZoneInventoryInDayOvr, $totalAdZoneInventoryInDay);

		if($totalAdZoneInventoryInDay <= 0 || $fwRateInDayOvr >= $fwRateOvr || $totalAdZoneInventoryAllTimeOvr >= $flightWebsiteInventoryOvr) {
		    return self::RESPONSE_TYPE_AD_ZONE_INVENTORY_LIMIT;
		}
		
		return TRUE;
	}

	/**
	 * check delivery status and condition of requested flight
	 */

	public function deliveredAdStatus($adID, $flightWebsite, $flight, $dateRange){
		$trackingModel = new Tracking;

		//kiểm tra độ tuổi
		if($flight->age && !$this->checkAge( json_decode($flight->age), Input::get('age') ) && !isLocal() ){
			return self::RESPONSE_TYPE_AGE_LIMIT;	
		}
		//kiểm tra giới tính
		if($flight->sex && !$this->checkGender($flight->sex, Input::get('g') ) && !isLocal() ){
			return self::RESPONSE_TYPE_GENDER_LIMIT;	
		}

		//kiểm tra vị trí địa lí
		if($flight->country && $flight->province && !$this->checkGeo($flight->country,$flight->province) ){
			return self::RESPONSE_TYPE_GEO_LIMIT;
		}
		//kiểm tra frequency capping
	    if( !empty($dateRange) && !$this->checkFrequencyCap($flight, $dateRange, $flightWebsite->id) && !isLocal() ){
			return self::RESPONSE_TYPE_FREQUENCY_CAPPED;
		}
		
		//TODO : kiểm tra channel
		// to do kiểm tra theo loại campaign : CPC hay CPM

		//if( $flight->cost_type == 'cpm' || $flight->cost_type == 'cpc' ){
			$event = Tracking::getTrackingEventType($flight->cost_type);
			
		    $overReport = FALSE;
			$checkInventory = $this->checkInventory($flight, $flightWebsite, $event, $dateRange);
			if($checkInventory !== TRUE){
				//full inventory trong ngày
				// $checkOverInventory = $this->checkOverInventory($flight, $flightWebsite, $event);
				// if($checkOverInventory !== TRUE){
					return $checkInventory;
				// }
				// else{
				// 	$overReport = TRUE;
				// }
			} else {
			    $checkOvrInventory = $this->checkOverInventory($flight, $flightWebsite, $event);
		        pr($checkOvrInventory);
    			if ($checkOvrInventory === TRUE) {
        		    $overReport = TRUE;
        		}
			}
		//}

		// WHEN READY TO DELIVERY
		//if( $flight->frequency_cap ){
		if (!empty($dateRange)) {
			$todayCapped = $trackingModel->getTodayFreCap($flight);
    		$now = strtotime(date('Y-m-d H:i'));
    		pr($dateRange);
    		foreach ($dateRange as $date) {
    			if(strtotime($date->start) <= $now &&  strtotime($date->end) >= $now){
    			    if($date->frequency_cap > 0){
    			        if ($trackingModel->getTimeFreCap($flight) == 0) {
	                    	$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
        				    $visitorId = $trackingModel->getVisitorId();
        				    $cacheKey = "Tracking:TimeFrequencyCap_{$flight->id}_{$visitorId}_{$flight->event}";
    	                    $cacheField = date('Y_m_d');
    	                    $redis->hSet($cacheKey, $cacheField, 0);
    	                    if ($date->frequency_cap_time > 0) {
    	                    	$redis->expire($cacheKey, $date->frequency_cap_time * 60);
    	                    } else {
    	                    	$redis->expire($cacheKey, 2);
    	                    }
        				}
        				
        				if ($todayCapped >= ($date->frequency_cap-1)) {
        				    $time = time();
        				    $expire = round((strtotime(date('Y-m-d 23:59:59', $time)) - $time)/60);
        				    $trackingModel->setTimeFreCap($flight, $expire);
        				}
        			}
    			}
    		}
			/*if(  $todayCapped >= $flight->frequency_cap_free &&  $flight->frequency_cap_time > 0 ){
				$trackingModel->rememberFrequencyCap($flightWebsite->id, $flight->frequency_cap_time);
			}*/
		}

		return $overReport ? self::DELIVERY_STATUS_OVER_REPORT : self::DELIVERY_STATUS_OK;
	}
	/**
	 * sort available flights base on priority and retargeting
	 */
    public function sortAvailableFlightWebsites($listFlightWebsites, $deliveryInfo){
        if (!empty($listFlightWebsites)) {
        	shuffle($listFlightWebsites);
        	foreach($listFlightWebsites as $k => $flightWebsite) {
        		if (isset($deliveryInfo['flights'])){
        			if (isset($deliveryInfo['flights'][$flightWebsite->flight_id])){
        				$flight = $deliveryInfo['flights'][$flightWebsite->flight_id];
        				if (isset($flight->audience)){
        					if (!empty($flight->audience)) {
        						$audience = json_decode($flight->audience, true);	
        						if ($audience['operator'] === 'in') {
			        				$temp = array($k => $flightWebsite);
								    unset($listFlightWebsites[$k]);
								    $listFlightWebsites = $temp + $listFlightWebsites;
			        			}
        					}        					
        				}
        			}
        		}
        	}
            /*$arrayKey = array_keys($listFlightWebsites);
            foreach($listFlightWebsites as $k =>$flightWebsite) {
                if (!empty($flightWebsite)) {
                    $tracking = new Tracking();
                    $cacheKey = "Retargeting";
                    $cacheField = "{$flightWebsite->advertiser_id}_{$tracking->getVisitorId()}";
                    $retargeting = $redis->hGet($cacheKey, $cacheField, false);
                    if ($retargeting) {
                        $url = $flightWebsite->retargeting_url;
                        $show = $flightWebsite->retargeting_show;
                        $number = $flightWebsite->retargeting_number;
                        $keyNumberTarget = "RetargetingNumber";
                        $fieldNumberTarget = "{$flightWebsite->advertiser_id}_{$flightWebsite->id}_{$flightWebsite->ad_id}_{$tracking->getVisitorId()}";
                        $retargeting_number =  $redis->hGet($keyNumberTarget, $fieldNumberTarget);
        
                        if (isset($retargeting->$url)) {
                            if($retargeting_number <= $number){
                                if ($show == 2) {
                                     unset($listFlightWebsites[$k]);
                                     unset($arrayKey[array_search($k, $arrayKey)]);
                                } elseif ($show == 1) {
                                    if ($k != 0) {
                                        $firstKey = array_shift($arrayKey);
                                        $tmpflight = $listFlightWebsites[$firstKey];
                                        $listFlightWebsites[$firstKey] = $flightWebsite;
                                        $listFlightWebsites[$k] = $tmpflight;
                                    } else {
                                        unset($arrayKey[array_search($k, $arrayKey)]);
                                    }
                                }
                            }else{
                               unset($listFlightWebsites[$k]);
                               unset($arrayKey[array_search($k, $arrayKey)]);
                            }
                        }
                    }
                }
            }*/
        }

		return $listFlightWebsites;
	}

    public function getAvailableAds($websiteID, $adFormat, $flightWebsiteID = '', $platform = ''){
		$retval = $this->getFlightWebsite($flightWebsiteID, $websiteID, $adFormat, $platform);
		
		return $retval;		
	}

	public function getFullFlightInfo($flightWebsites, $websiteID, $adFormat, $renewCache = false){
		if(empty($flightWebsites)){
			return false;
		}
		
		$retval = [];
		foreach ($flightWebsites as $k => $fw) {
		    if (!empty($fw)) {
    		    $flight = $this->getFlight($fw->flight_id);
    		    if ($flight && $flight->status) {
        			$retval['flights'][$fw->flight_id] = $flight;
    		        $retval['flightDates'][$fw->flight_id] = $this->getFlightDate($fw->flight_id);
    		        $adID = $retval['flights'][$fw->flight_id]->ad_id;			
        			$retval['ads'][$adID] = $this->getAd($adID);	
    		    }	
		    }
		}
		
		return $retval;
	}

	public function getFlightWebsite($flightWebsiteID = '', $websiteID, $adFormatID, $platform = '', $renewCache = false){
	    $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
	    $cacheKey = "FlightWebsite_{$websiteID}_{$adFormatID}";
	    if ($platform != '') {
	        $cacheKey .= "_{$platform}"; 
	    }
		$cachField = $flightWebsiteID;
		if ($cachField != '') {
		    $retval = $redis->hMget($cacheKey, array($cachField));
		} else {
		    $retval = $redis->hGetAll($cacheKey);
		}
		
		return $retval;
	}
	
	public function getAdzone($zoneID, $renewCache = false){
	    $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
		$cacheKey = "Adzone";
	    $cacheField = $zoneID;
		$retval = $redis->hGet($cacheKey, $cacheField);
		$retval = $redis->hGet($cacheKey, $cacheField);
		
		return $retval;
	}

	public function getAd($adID, $renewCache = false){
	    $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
		$cacheKey = "Ad";
		$cacheField = $adID;
		$retval = $redis->hGet($cacheKey, $cacheField);
		return $retval;

	}

	public function checkFlightDate($listFlightDates, $flight){
		$retval = false;
		$now = strtotime(date('Y-m-d'));
		foreach ($listFlightDates as $flightDate) {
			if(strtotime($flightDate->start) <= $now &&  strtotime($flightDate->end) >= $now){
				//check active hour
				// -- Start -- Phuong-VM -- Comment -- 12-05-2015
				/*$startTime = $flight->start_hour ? intvalFromTimeText($flight->start_hour) : 0;
				$endTime = $flight->end_hour ? intvalFromTimeText($flight->end_hour) : 0;
				$nowTime = intvalFromTimeText(date('H:i:s'));
				if( ($startTime == 0 || $startTime <= $nowTime) && ($endTime == 0 || $endTime >= $nowTime) ){
					//passed
					$retval = true;
					break;
				}*/
				// -- Start -- Phuong-VM -- Comment -- 12-05-2015
				
				// -- Start -- Phuong-VM -- add -- 12-05-2015
				//$listHours = json_decode($flightDate->hour);
				if (!empty($flightDate->hour)) {
    				foreach ($flightDate->hour as $hour) {
    				    $startTime = $hour->start ? intvalFromTimeText($hour->start) : 0;
    				    $endTime = $hour->end? intvalFromTimeText($hour->end) : 0;
    				    $nowTime = intvalFromTimeText(date('H:i'));
        				if( ($startTime == 0 || $startTime <= $nowTime) && ($endTime == 0 || $endTime >= $nowTime) ){
        					//passed
        					$retval = true;
        					break;
        				}
    				}
				} else {
				    $retval = true;
				}
				// -- Start -- Phuong-VM -- add -- 12-05-2015
			}
		}
		return $retval;
	}

	public function getAlternateAds($zoneID, $renewCache = false){
	    $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_3', '6379'), false);
		$cacheKey = "PublisherAlternateAd";
		$cacheField= $zoneID;
		$retval = $redis->hGet($cacheKey, $cacheField);
		return $retval;
	}
    
    /**
     * 
     * Get flight
     * @param int $id
     * @param bool $renewCache
     */
    public function getFlight($id, $renewCache=false) {
        $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
        $cacheKey = "Flight";
        $cacheField= $id;
        $retval = $redis->hGet($cacheKey, $cacheField);
        return $retval;
    }
    
    public function getFlightDate($flightID, $renewCache=false) {
        $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
        $cacheKey = "FlightDate";
        $cacheField = $flightID;
        $retval = $redis->hGet($cacheKey, $cacheField);
        return $retval;
    }
	
    public function getFullFlightWebsite($flightWebsiteID, $websiteID, $adFormatID, $platform = ''){
		$retval = array();
        $flightWebsite = $this->getFlightWebsite($flightWebsiteID, $websiteID, $adFormatID, $platform);
        if(!empty($flightWebsite) && count($flightWebsite) > 0){
		    $retval = array_shift($flightWebsite);
		    
		    if ($retval) {
			    $retval->flight = $this->getFlight($retval->flight_id);		   
    			if($retval->flight){
    				$retval->ad     = $this->getAd($retval->flight->ad_id);
    			}
		    }
		}
		return $retval;
	}
	
	/**
	 * 
	 * Get publisher's site
	 * @param int $id
	 * @param bool $renewCache
	 */
	public function getPublisherSite($id, $renewCache=false) {
	    $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
	    $cacheKey = "PublisherSite";
	    $cacheField = $id;
		$retval = $redis->hGet($cacheKey, $cacheField);
		return $retval;
	}
	
    /**
     * 
     * Get add zone
     * @param int $zoneID
     * @param bool $renewCache
     */
	public function getPublisherAdZone($zoneID, $renewCache = false){
	    $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
		$cacheKey = "PublisherAdZone";
		$cacheField = $zoneID;
		$retval = $redis->hGet($cacheKey, $cacheField);
		return $retval;
	}
	
	/**
	 * 
	 * Get campaign
	 * @param int $campaignID
	 * @param bool $renewCache
	 */
	public function getCampaign($campaignID, $renewCache = false) {
	    $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
	    $cacheKey = "Campaign";
	    $cacheField = $campaignID;
		$retval = $redis->hGet($cacheKey, $cacheField);
		return $retval;
	}
	
	/**
	 * Detect device
	 */
	
	public function getPlatform() {
	    $detect = new MobileDetect;
	    if ($detect->isMobile() || $detect->isTablet()){
	        return 'mobile';
	    } else {
	        return 'pc';
	    }
	}
	
	public function changePercentage($value, $total, $type = '') {
	    if ($total <= 0) {
	        return 0;
	    } else {
	        $rate = $value / $total * 100;
	        switch($type) {
	            case 1:
	                $rate = floor($rate);
                    break;
                case 2:
	                $rate = round($rate, 4);
                    break;
                case 3:
	                $rate = ceil($rate);
                    break;
                default:
	                $rate = round($rate, 4);
                    break;
	        }
	        
	        return $rate;
	    }
	    
	}
	
	/**
	 * 
	 * Get category
	 * @param int $categoryID
	 * @param bool $renewCache
	 */
	public function getCategory($categoryID, $renewCache = false) {
	    $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
	    $cacheKey = "Category";
	    $cacheField = $categoryID;
		$retval = $redis->hGet($cacheKey, $cacheField);
		return $retval;
	}
	
    public function getAdFormat($adFormatID, $renewCache = false) {
        $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_2', '6379'), false);
	    $cacheKey = "Ad_Format";
	    $cacheField = $adFormatID;
		$retval = $redis->hGet($cacheKey, $cacheField);
		return $retval;
	}
	
	public function getCamPaignInfoByPublisher($zoneID, $flightWebsiteID, $adFormatID) {
	    $adZone = $this->getAdzone($zoneID);
	    $retval = array();
	    if ($adZone) {	        
	        $flightWebsite = $this->getFullFlightWebsite($flightWebsiteID, $adZone->publisher_site_id, $adFormatID);
	        $retval['flight_name'] = isset($flightWebsite->flight->name) ? str_replace(' ', '_', $flightWebsite->flight->name) : '';
	        if (!empty($flightWebsite->flight->category_id)) {
	            $category = $this->getCategory($flightWebsite->flight->category_id);
	            if ($category && !empty($category->name)) {
	                $retval['category_name'] = str_replace(' ', '_', strtolower($category->name));
	            }
	        } else {
	            $campaign = $this->getCampaign($flightWebsite->flight->campaign_id);
			    if (!empty($campaign)) {
			        $category = $this->getCategory($campaign->category_id);
			        $retval['category_name'] = str_replace(' ', '_', strtolower($category->name));
			    }
	        }
	    }
	    
	    return $retval;
	}
	
	public function checkPlatform($ad) {
        if (empty($ad) || empty($ad->platform)) {
            return self::PLATFORM_TYPE_INVALID;
        }
        $detect = new MobileDetect();
        $arrPlatform = json_decode($ad->platform);
        $type = '';
        if ($detect->isMobile() || $detect->isTablet()) {
            $type = 'mobile';
            if ((in_array('mobile_ios', $arrPlatform) || in_array('mobile_android', $arrPlatform)) && !in_array('mobile', $arrPlatform)) {
            	if ($detect->isiOS()) {
	            	 $type = 'mobile_ios';
	            }
	            if ($detect->isAndroidOS()) {
	            	 $type = 'mobile_android';
	            }
            }
        } else {
            $type = 'pc';
        }
        if (!empty($arrPlatform) && in_array($type, $arrPlatform)) {
            return TRUE;
        } else {
            return self::PLATFORM_TYPE_INVALID;
        }                
	}

	public function getUrlTrack3rd($data) {
		if (isset($data['ec'])){
			if ($data['ec'] == 0){
				return array();
			}
		}
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $domain = getWebDomain($referer);
        $urls = array();
		$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_3', '6379'), false);
		$cacheKey = "URLTrack3rd";
		if ($redis->exist($cacheKey)){
			$retval = $redis->get($cacheKey);	
		}else{
			$retval = DB::table('url_track_3rd')->select('id', 'url', 'website', 'amount','run')
												->where('active', 1)
												->where('amount', '>', 0)
												->get();
			$redis->set($cacheKey, $retval);
		}		
		
		if(!empty($retval)){
			foreach ($retval as $value) {
				$bCheckWeb = false;
				if ($value->website){
					$website = explode("\n", $value->website);
					foreach ($website as $v) {
						if (strpos($v, $domain) !== false){
							$bCheckWeb = true;
							break;
						}
					}
				}else{
					$bCheckWeb = true;
				}

				if (!$bCheckWeb){
					continue;
				}

				$cacheKeyTotal = 'URLTrack3rd.' .$value->id;
				$cacheKeyTotalDay = $cacheKeyTotal . '.' .date('Ymd');
				$cacheKeyTotalGet = ($value->run == 'day') ? $cacheKeyTotalDay : $cacheKeyTotal;
				$total = $redis->get($cacheKeyTotalGet);
				if ($total < $value->amount){
					$bCheckTotal = true;
				}else{
					$bCheckTotal = false;
				}

				if ($bCheckTotal){
					$urls[] = $value->url;
					$redis->increment($cacheKeyTotal);
					$redis->increment($cacheKeyTotalDay);
				}
			}
		}
		pr($urls);
		return $urls;
	}

	public function checkTag($tag_flight, $tag_pub) {
		$arrTagFlight = explode(',', str_replace(' ', '', $tag_flight));
		$arrTagPub = explode(',', str_replace(' ', '', $tag_pub));

		foreach ($arrTagPub as $tag) {
			if (in_array($tag, $arrTagFlight)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	public function rotatorPercentAd($zoneID, $listAlternateAd) {
        $arrIndex = array();
        $cnt = 0;
        $cookieKey = "YoMediaCookie3rd{$zoneID}";
        $cnt_alternate = isset($_COOKIE[$cookieKey]) ? $_COOKIE[$cookieKey] : 0;
        $pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
        foreach ($listAlternateAd as $alternateAd) {
        	$weight = !empty($alternateAd->weight) ? $alternateAd->weight : 1;
        	for($i = 0; $i < $weight; $i++) {
        		$arrIndex[$cnt] = $alternateAd->code;
        		$cnt++;
        	}
        }

        if ($cnt_alternate >= count($arrIndex)) {
        	$cnt_alternate = 0;
        }
        setcookie($cookieKey, $cnt_alternate + 1, time() +(86400*365), '/', getWebDomain(DOMAIN_COOKIE));

        return $arrIndex[$cnt_alternate];
    }
}