<?php namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Conversion;
use App\Models\Tracking;
use App\Models\RawTrackingSummary;
use App\Models\VAST;
use RedisHelper;
use App\Models\RedisBaseModel;
use Input;
use Cookie;
use App\Models\RawTrackingAudience;

class DeliveryController extends Controller
{

	public function __construct(){
		parent::__construct(pathinfo(dirname(__DIR__), PATHINFO_BASENAME));
	}

    /**
     * @return mixed
     */
    public function makeOva(){
		$data = Input::all();
		$deliveryModel   = new Delivery;
        
		if( (new MakeOvaHandle())->checkInputValid( $data ) ){
		    $this->data['ad'] = $deliveryModel->getAd($data['aid']);
			\View::addLocation(base_path() . '/resources/views/delivery');
			if(Input::get('wid') != 99999999){
				$this->data['vast'] = url()."/make-vast?" . $_SERVER['QUERY_STRING'];
			}
			else{
				$this->data['vast'] = 'http://yomedia.vn/demo/demo_vast.xml';	
			}
			$body = \View::make('ova', $this->data);
			return response($body, 200)
              ->header('Content-Type', 'text/xml; charset=UTF-8');
		}

		exit();

	}

	public function makeVast()
	{
		$adID              = Input::get('aid', 0);
		$flightWebsiteID   = Input::get('fpid', 0);
		$publisherAdZoneID = Input::get('zid', 0);
		$checksum          = Input::get('cs');
		$isOverReport      = Input::get('ovr', '');
		$ref               = Input::get('ref', '');
		
	    $showBanner = showBanner();
		if ($showBanner !== FALSE) {
		    $flightWebsiteID = $showBanner;
		}
    	return (new VAST)->makeVAST($adID, $flightWebsiteID, $publisherAdZoneID, $checksum, $isOverReport, $ref);
	}

	public function adsProcess(){
	    $startProcess = microtime(1);
	    $this->layout   = null;
		$response       = null;
		$responseType   = '';
		$expandFields   = array();
		$deliveryStatus = '';

		$requestType     = Input::get('rt', Delivery::REQUEST_TYPE_AD);
		$flightWebsiteID = Input::get('fpid', '');
		$zoneID          = Input::get('zid', 0);
		$websiteID       = Input::get('wid', 0);
		$platform        = Input::get('plf', '');
		$tag             = Input::get('tag', '');
		
		$data            = Input::all();
		$trackingModel   = new Tracking;
		$deliveryModel   = new Delivery;
		$isOverReport = $data['ovr'] = false;
		$showBanner = showBanner();
		if ($showBanner !== FALSE) {
		    $flightWebsiteID = $showBanner;
		}
		//$uuid = $trackingModel->getVisitorId();

		//ghi log trước khi xử lý
		//$logPreProcess = $trackingModel->logPreProcess($requestType, $data);
		
		// if($continueProcess){
			//kiểm tra referrer
			$hostReferer = $trackingModel->getRequestReferer();
			$responseType = $trackingModel->checkPreProcess($requestType, $hostReferer, $zoneID);
			//pre validate ok
			if(empty($responseType)){
				//read redis 1
				$data['ref'] = $hostReferer;
				$adZone = $deliveryModel->getAdzone($zoneID);
				if($adZone && !empty($adZone->site)){
					$expandFields['ad_format_id'] = $adZone->ad_format_id;
					if($adZone->publisher_site_id == $websiteID){
						//kiểm tra referrer đúng với site đã đăng ký
						// if(1){//test only
						$publisherSite = $deliveryModel->getPublisherSite($adZone->publisher_site_id);
						pr($publisherSite);
						if( !$publisherSite->domain_checking || isSameDomain($hostReferer, getWebDomain($adZone->site->url) ) || isLocal() ){
						 	//    if ($platform == '') {
							//     $platform = $deliveryModel->getPlatform();
							// }
							$platform = '';

							//read redis 1
							$flightWebsites = $deliveryModel->getAvailableAds($adZone->publisher_site_id, $adZone->ad_format_id, $flightWebsiteID, $platform);
							pr($flightWebsites);
							if($flightWebsites){								
								//sort available flights base on priority and retargeting
								//TO DO retargeting
								$flightWebsites = $deliveryModel->sortAvailableFlightWebsites($flightWebsites);
								//lấy ad từ list thỏa điều kiện để trả về
								$deliveryInfo = $deliveryModel->getFullFlightInfo($flightWebsites, $adZone->publisher_site_id, $adZone->ad_format_id);
								pr($deliveryInfo);
								$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_6', '6379'), false);
								foreach ($flightWebsites as $k => $flightWebsite) {
									if(!empty($flightWebsite) && !empty($deliveryInfo['flightDates'][$flightWebsite->flight_id]) && !empty($deliveryInfo['flights'][$flightWebsite->flight_id])){

										$flightDates = $deliveryInfo['flightDates'][$flightWebsite->flight_id];
										$flight      = $deliveryInfo['flights'][$flightWebsite->flight_id];
										$ad          = $deliveryInfo['ads'][$flight->ad_id];
										if ($deliveryModel->checkPlatform($ad) === TRUE || isLocal()) {
    										$checkFlightDate = $deliveryModel->checkFlightDate($flightDates, $flight);
    										//flight date ok
    										if($checkFlightDate){
    											$deliveryStatus = $deliveryModel->deliveryAd($ad, $flightWebsite, $flight, $flightDates);
    											if($deliveryStatus == Delivery::DELIVERY_STATUS_OK || $deliveryStatus == Delivery::DELIVERY_STATUS_OVER_REPORT){
    											    
    										        if (empty($data['ec']) && !empty($ad->vast_include) && !empty($ad->video_wrapper_tag)) {
    										            $xmlVastTag = $this->getVastAdTagUri($ad->id, $this->replaceParam($ad->video_wrapper_tag));
    										            if (empty($xmlVastTag)) {
        										            continue;
    										            }
    										        }

    										        if (!empty($flight->filter)) {
    										        	if (!$deliveryModel->checkTag($flight->filter, urldecode($tag))) {
    										        		$deliveryStatus = Delivery::TAG_TYPE_INVALID;
    										        		continue;
    										        	}
    										        }

    										        //Check retargeting
										        	if (!empty($flight->audience)) {
										        		$check = false;
									        			$audience = json_decode($flight->audience, true);
									        			if (!empty ($audience['audience_id'])) {
								        					if (isset($_COOKIE["yoAu_{$audience['audience_id']}"]) && !empty($_COOKIE["uuid"])) {
								        						if ($_COOKIE["yoAu_{$audience['audience_id']}"] === '1' || substr($_COOKIE["yoAu_{$audience['audience_id']}"], 0, 2) === '1.'){
								        							$check = true;						        					
								        						}								        						
									        				}
									        				if ($audience['operator'] === 'not in') {
								        						$check = !$check;
								        					}
								        				}
								        				
								        				if ($check === false) {
								        					$deliveryStatus = Delivery::RESPONSE_TYPE_AUDIENCE_LIMIT;
								        					continue;
								        				}
										        	}										        	

    												//trả về ad này
    												pr($flightWebsite);
    												$serveAd      = $ad;
    												$data['ad']   = $ad;
    												$data['aid']  = $ad->id;
    												$data['fpid'] = $flightWebsite->id;
    												$data['flight'] = $flight;
    												//over report
    												if($deliveryStatus == Delivery::DELIVERY_STATUS_OVER_REPORT){
    													$data['ovr'] = $isOverReport = true;
    												}
    												$responseType = Delivery::RESPONSE_TYPE_ADS_SUCCESS;
    											    
    												break;
    											}
    										}
    										else{
    											$deliveryStatus = Delivery::RESPONSE_TYPE_FLIGHTDATE_NOT_AVAILABLE;
    										}
										} else {
										    $deliveryStatus = Delivery::PLATFORM_TYPE_INVALID;
										}
									}
								}
								//endforeach
							}
							//no ads available
							if($responseType != Delivery::RESPONSE_TYPE_ADS_SUCCESS){
								$responseType = Delivery::RESPONSE_TYPE_NOT_AVAILABLE;
							}
						}
						else{
							$responseType = Delivery::RESPONSE_TYPE_REFERRER_NOT_MATCH;
						}
					}
				}
			}
			
		// }
		//invalid ads request
		if(empty($responseType)){
			$responseType = Delivery::RESPONSE_TYPE_INVALID;
		}elseif($responseType == Delivery::RESPONSE_TYPE_ADS_SUCCESS){
			$expandFields = array(
				'flight_id'				=>	$flightWebsite->flight_id,
				'ad_format_id'         	=>	$adZone->ad_format_id,
				'ad_id'                	=>	$flight->ad_id,
				'campaign_id'          	=> 	$flight->campaign_id,
				'publisher_ad_zone_id' 	=> 	$adZone->id,
				'flight_website_id'  	=> 	$flightWebsite->id,
				'website_id'     		=> 	$flightWebsite->website_id,
			    'publisher_base_cost'   => 	$flightWebsite->publisher_base_cost,
			);
			if($isOverReport){
				$expandFields['ovr'] = 1;
			}

			$expandFields['checksum'] = $checksum = $trackingModel->makeChecksumHash($flightWebsite->id);
			$eventChecksum = Delivery::RESPONSE_TYPE_ADS_SUCCESS;
			$trackingModel->setChecksumTrackingEvent($checksum, Delivery::RESPONSE_TYPE_ADS_SUCCESS);

			(new RawTrackingSummary())->addSummary('ads_request', $flightWebsite->website_id, $adZone->id, $adZone->ad_format_id, $flightWebsite->flight_id, $flightWebsite->id, $flight->ad_id, $flight->campaign_id, $flightWebsite->publisher_base_cost, $isOverReport);

		}

		$data['url_track_ga'] = $deliveryModel->getUrlTrackGA();
		//serve Ad
		// if(0){
		if(!empty($serveAd)){
			\View::addLocation(base_path() . '/resources/views/delivery');
			if( isset( $data['ec'] ) ){
				if( $data['ec'] == 1 ){
					$data['checksum'] = $expandFields['checksum'];
					$data['element_id'] = isset($adZone->element_id) ? $adZone->element_id : '';
					$data['category_name'] = '';
					$adFormat = $deliveryModel->getAdFormat($data['ad']->ad_format_id);
					if (empty($data['flight']->category_id)) {				    
					    $campaign = $deliveryModel->getCampaign($data['flight']->campaign_id);
					    if (!empty($campaign)) {
					        $category = $deliveryModel->getCategory($campaign->category_id);
					        $data['category_name'] = isset($category->name) ? str_replace(' ', '_', strtolower($category->name)) : '';
					    }
					} else {
					    $category = $deliveryModel->getCategory($data['flight']->category_id);
					    if (!empty($category)) {
					        $data['category_name'] = isset($category->name) ? str_replace(' ', '_', strtolower($category->name)) : '';
					    }					    
					}
					$data['flight_name'] = isset($data['flight']->name) ? str_replace(' ', '_', $data['flight']->name) : '';
					$data['ad_format'] = isset($adFormat->name) ? str_replace(' ', '_', $adFormat->name) : '';
					$data['publisher_domain'] = $hostReferer;
					$data['rd'] = str_random(40);

					$this->data['data'] = $data;

					$data['type'] = $serveAd->ad_view ? $serveAd->ad_view : strtolower($data['type']);

					if( Input::get('test') == 1 ){
			            return response(\View::make($data['type'], $this->data), 200)->header('Content-Type','text/javascript; charset=UTF-8');
					}else{
					    $response = response(\View::make($data['type'], $this->data), 200)->header('Content-Type','text/javascript; charset=UTF-8');;
					}
				}else{
			    	$response = (new VAST)->makeVAST($data['aid'], $data['fpid'], $data['zid'], $expandFields['checksum'], $isOverReport);
				}
			}

		}
		else{
			//TO DO : return backup ads
			if(!empty($adZone)){
				$adZone->alternateAds = !empty($adZone->alternateAds) ? $adZone->alternateAds : $deliveryModel->getAlternateAds($adZone->id);

				if( isset($adZone) && !empty($adZone->alternateAds) )
				{
					if( isset( $data['ec'] )  && !$data['ec']){
						// TO DO : return backup vast
						$response = (new VAST)->makeEmptyVast();
					}
					else{
						$this->data['listAlternateAd'] = $adZone->alternateAds;
						$this->data['zid'] = $adZone->id;
						$this->data['data']['url_track_ga'] = $data['url_track_ga'];
						\View::addLocation(base_path() . '/resources/views/delivery');
						$response = response(\View::make('rotator', $this->data), 200)->header('Content-Type','text/javascript; charset=UTF-8');;
					}
				}
				else
				{
					if( isset( $data['ec'] )  && !$data['ec']){
						$response = (new VAST)->makeEmptyVast();
					} else {
						$this->data['data']['url_track_ga'] = $data['url_track_ga'];
						\View::addLocation(base_path() . '/resources/views/delivery');
						$response = response(\View::make('url_track_ga', $this->data), 200)->header('Content-Type','text/javascript; charset=UTF-8');
					}
				}

				(new RawTrackingSummary())->addSummaryRequestEmptyAd($adZone->id, $adZone->ad_format_id, $websiteID, $isOverReport);
			}
		}
		
		//ghi log process
		//$trackingModel->logAfterProcess($responseType, $expandFields, $logPreProcess);
		
		//response to client
		$endProcess = microtime(1);
        pr("Times: " . ($endProcess - $startProcess));
		pr($deliveryStatus);
		pr($responseType,1);
		return $response;
	}

	public function trackEvent(){
		// return '';
		// ignore_user_abort(true);
		$this->layout   = null;
		$response       = null;
		$responseType   = '';
		$expandFields   = array();
		$deliveryStatus = '';

		$event           = strtolower(Input::get('evt', ''));
		$requestType     = Input::get('rt', Delivery::REQUEST_TYPE_TRACKING_BEACON);
		$checksum        = Input::get('cs', '');
		$flightWebsiteID = Input::get('fpid', 0);
		$zoneID          = Input::get('zid', 0);
		$isOverReport 	 = Input::get('ovr');
		$platform        = Input::get('plf', '');
		$beacon          = Input::get('bc', '');
		$hostReferer     = Input::get('ref', '');

		//custom code for VIB 068 Relaunch form complete tracking
		if(Input::get('wid') == 48 && $event == 'complete' ){
			$flightWebsiteID = 172;
			$event           = "impression";
		}

		$trackingModel = new Tracking;
		$deliveryModel = new Delivery;
		//ghi log trước khi xử lý
		//$logPreProcess = $trackingModel->logPreProcess($requestType, Input::get());
				
		// if($logPreProcess){
		if(1){
			//kiểm tra referrer
			if (empty($hostReferer)) {
			    $hostReferer = $trackingModel->getRequestReferer();
			}
			$responseType = $trackingModel->checkPreProcess($requestType, $hostReferer, $zoneID);
			if(empty($responseType)){
				$adZone = $deliveryModel->getAdzone($zoneID);

				if($adZone){
					$publisherSite = $deliveryModel->getPublisherSite($adZone->publisher_site_id);
					if( !$publisherSite->domain_checking || isSameDomain($hostReferer, getWebDomain($adZone->site->url) )  || isLocal() || $adZone->id == 241 || $platform === 'mobile_app'){
				        //    if ($platform == '') {
					    //     $platform = $deliveryModel->getPlatform();
						// }
						$platform = '';
					    $flightWebsite = $deliveryModel->getFullFlightWebsite($flightWebsiteID, $adZone->publisher_site_id, $adZone->ad_format_id, $platform);
						if($flightWebsite){
							//checksum
							if($flightWebsite->flight->ad_format_id == Delivery::TRACKING_CODE_ADID){
								$responseType = Delivery::RESPONSE_TYPE_TRACKING_BEACON_SUCCESS;
							}
							elseif($trackingModel->isValidTrackingBeacon($checksum, $event)){
								$responseType = Delivery::RESPONSE_TYPE_TRACKING_BEACON_SUCCESS;
							}
							else{
								$responseType = Delivery::RESPONSE_TYPE_CHECKSUM_ERROR;
							}
						}
						//else invalid
					}
				}
			}
			if(empty($responseType)){
				$responseType = Delivery::RESPONSE_TYPE_INVALID;
			}elseif($responseType == Delivery::RESPONSE_TYPE_TRACKING_BEACON_SUCCESS){
				$expandFields = array(
					'flight_id'				=>	$flightWebsite->flight_id,
					'ad_format_id'         	=>	$adZone->ad_format_id,
					'ad_id'                	=>	$flightWebsite->flight->ad_id,
					'campaign_id'          	=> 	$flightWebsite->flight->campaign_id,
					'publisher_ad_zone_id' 	=> 	$adZone->id,
					'flight_website_id'  	=> 	$flightWebsite->id,
					'website_id'     		=> 	$flightWebsite->website_id,
				    'publisher_base_cost'   => 	$flightWebsite->publisher_base_cost,
				);

				$expandFields['checksum'] = $checksum;
				$eventChecksum = $event;
				$trackingModel->setChecksumTrackingEvent($checksum, $event);

				//update tracking summary
				$rawTrackingSummary = new RawTrackingSummary();
				if ('impression' == $event && !empty($beacon) && !empty($flightWebsite->ad->third_impression_track)) {
    			    $thirdImpressionTrackArr = explode("\n", $flightWebsite->ad->third_impression_track);
    			    $arrCurl = array();
    			    foreach ($thirdImpressionTrackArr as $item) { 
    			        array_push($arrCurl, trim($this->replaceParam($item)));
    			    }
    			    multipleThreadsRequest($arrCurl);
				}
				$rawTrackingSummary->addSummary($event, $flightWebsite->website_id, $adZone->id, $adZone->ad_format_id, $flightWebsite->flight_id, $flightWebsite->id, $flightWebsite->flight->ad_id, $flightWebsite->flight->campaign_id, $flightWebsite->publisher_base_cost, $isOverReport);

				$uuid = $trackingModel->getVisitorId();				
				
		        if ('impression' === $event || 'click' === $event) {
		        	$time = time();		        	
		        	//Collection data
		        	if (!empty($flightWebsite->ad->audience_id)) {
		        		$audience_id = $flightWebsite->ad->audience_id;		        		
		        		$cookie = isset($_COOKIE["yoAu_{$audience_id}"]) ? $_COOKIE["yoAu_{$audience_id}"] : '';
		        		if (!$cookie || substr($cookie, 0, 2) === "0." || $cookie === '1') {
			        		setcookie("yoAu_{$audience_id}", "1." .$time, $time+(86400*365), '/', getWebDomain(DOMAIN_COOKIE));
		        			$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_6', '6379'), false);
	        				$redis->pfadd("au.$audience_id", array($uuid));	        				
			        	}     	
			        	if (substr($cookie, 0, 2) === '1.'){			        		
			        		$time = substr($cookie, 2);
			        	}
			        	$rawTrackingAudience= new RawTrackingAudience();
		        		$rawTrackingAudience->addAudience($uuid, $audience_id, $flightWebsite->ad->id, $time, $event);
		        	}
		        	//Tracking audience
		        	if (!empty($flightWebsite->flight->audience)) {
		        		$audience = json_decode($flightWebsite->flight->audience, true);
		        		$cookie = isset($_COOKIE["yoAu_{$audience['audience_id']}"]) ? $_COOKIE["yoAu_{$audience['audience_id']}"] : '';
		        		if (substr($cookie, 0, 2) === '1.' || substr($cookie, 0, 2) === '0.'){
			        		$time = substr($cookie, 2);
			        	}
		        		if ($cookie === '1') {
		        			setcookie("yoAu_{$audience['audience_id']}", "1." .$time, $time+(86400*365), '/', getWebDomain(DOMAIN_COOKIE));
		        		}
		        		if ($audience['operator'] == 'not in'){		        			
		        			setcookie("yoAu_{$audience['audience_id']}", "0." .$time, $time+(86400*365), '/', getWebDomain(DOMAIN_COOKIE));	
		        		}		        		
		        		$rawTrackingAudience= new RawTrackingAudience();
		        		$rawTrackingAudience->addAudience($uuid, $audience['audience_id'], $flightWebsite->ad->id, $time, $event);
		        	}		        	
		        }

				//udpate inventory
				//$inventoryMetric = $trackingModel->getTrackingEventType($flightWebsite->flight->cost_type);
				$inventoryMetric = $trackingModel->getTrackingEventType($flightWebsite->flight->cost_type);
				if($event == $inventoryMetric){
					//$trackingModel->updateInventory($flightWebsite->flight->id, $flightWebsite->id, $inventoryMetric, $isOverReport);
					$rawTrackingSummary->updateInventory($flightWebsite->flight->id, $flightWebsite->id, $inventoryMetric, $isOverReport);
				}
			    if (isset($flightWebsite->flight->event) && $flightWebsite->flight->event != '') {
				    $flightEvent = $flightWebsite->flight->event;
				} else {
				    // Truong hop nhung flight truoc day khong co setting event
				    $flightEvent = $inventoryMetric;
				}
				    
				if($event == $flightEvent){
					//incre today capped this ad by 1
					$trackingModel->incFreCap($flightWebsite->flight);
				}

				$uniqueEvent = '';
				//unique impression
				if($event == 'impression'){
					$uniqueEvent = 'unique_impression';
					$expandFields['unique_impression'] = $trackingModel->isUniqueImpression($flightWebsite->id);

					// update number show retargeting
                    // $deliveryModel->updateRetargeting($flightWebsite->flight, $trackingModel->getVisitorId());
				}
				//unique click
				if($event == 'click'){
					$uniqueEvent = 'unique_click';
					$expandFields['unique_click'] = $trackingModel->isUniqueClick($flightWebsite->id);
				}

				//add summary unique click or impression
				if(!empty($expandFields[$uniqueEvent])){
					$rawTrackingSummary->addSummary($uniqueEvent, $flightWebsite->website_id, $adZone->id, $adZone->ad_format_id, $flightWebsite->flight_id, $flightWebsite->id, $flightWebsite->flight->ad_id, $flightWebsite->flight->campaign_id, $flightWebsite->publisher_base_cost, $isOverReport);
				}
			}
		}

		//ghi log process
		//$logAfterProcess = $trackingModel->logAfterProcess($responseType, $expandFields, $logPreProcess);

		//response to client
		pr($expandFields);
		pr($responseType,1);
		if($event == 'click' && Input::get('to')){
			//redirect to ad's destination url on click event
			if (!empty($beacon) && !empty($flightWebsite->ad->third_click_track)) {
			    $thirdClickTrackArr = explode("\n", $flightWebsite->ad->third_click_track);
			    
			    $arrCurl = array();
			    foreach ($thirdClickTrackArr as $item) { 
			        array_push($arrCurl, trim($this->replaceParam($item)));
			    }
			    multipleThreadsRequest($arrCurl);
			}
			$conversionModel   = new Conversion;
			$conversionCampaign = array();
			if(!empty($expandFields['campaign_id'])) {
			    $conversionCampaign = $conversionModel->getCampaignConversion($expandFields['campaign_id']);
			}
			
			if (!empty($conversionCampaign)) {
			    $infoConversion = array('wid' => $expandFields['website_id'],'bid'=>$expandFields['ad_id']);
			    $cookieKey = "Conv_{$expandFields['campaign_id']}";
			    return redirect(urldecode(Input::get('to')))->withCookie(cookie($cookieKey, json_encode($infoConversion), 60));
			} else {
			    return redirect(urldecode(Input::get('to')));
			}
		}
		else{
			//return 1x1 transparent GIF
			$response = $trackingModel->outputTransparentGif();
			// exit();
		}
	}
	
	public function retargeting()
    {
        $adv = Input::get("adv");
        
        if($adv <= 0){
            return "";
        }
        
        $referer_url = "";
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer_url = $_SERVER['HTTP_REFERER'];
        }

        if ($referer_url != "") {
            $tracking = new Tracking();
            $cacheKey = "retargeting";
            $cacheField= "{$adv}_{$tracking->getVisitorId()}";
            $key_referer_url = $referer_url;
            $cache = RedisHelper::hGet($cacheKey, $cacheField, false);
            if ($cache == null) {
                $array = new stdClass();
                $array->$referer_url = $referer_url;
                RedisHelper::hSet($cacheKey, $cacheField, $array);
            }else{
                $array = $cache;
                $array->$referer_url = $referer_url;
                RedisHelper::hSet($cacheKey, $cacheField, $array);
            }
        }
    }
    
    public function renderVast()
	{
		$adID              = Input::get('aid', 0);
		$flightWebsiteID   = Input::get('fpid', 0);
		$publisherAdZoneID = Input::get('zid', 0);
		$checksum          = Input::get('cs');
		$isOverReport      = Input::get('ovr', '');
		
	    $hostReferer = '*';
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
            $hostReferer = parse_url($url, PHP_URL_SCHEME) . '://' . getWebDomain($url);
        }
		
		$header['Content-Type']                     = 'application/xml';
        $header['Access-Control-Allow-Origin']      = $hostReferer;
        $header['Access-Control-Allow-Credentials'] = 'true';
        $header['Cache-Control']                    = 'no-store, no-cache, must-revalidate, max-age=0';
        $header['Cache-Control']                    = 'post-check=0, pre-check=0';
        $header['Pragma']                           = 'no-cache';
		
		$ad  = (new Delivery())->getAd($adID);
		
		if ($ad) {
		    $eventArr = [
                'impression','start','firstQuartile','midpoint','thirdQuartile',
                'complete', 'mute', 'unmute', 'pause', 'fullscreen'
            ];
            try {
        		$path = "http://static.yomedia.vn/xml/{$ad->campaign_id}/";
        	    $xml_filename = str_replace(' ', '_', strtolower($ad->name)) . '.xml';
                $xml_file = file_get_contents("{$path}{$xml_filename}");
                if(!empty($xml_file)) {
                    foreach ($eventArr as $event) {
                        $check_key = "[yomedia_{$event}_url]";
                        if(strpos($xml_file, $check_key) !== FALSE){
                           $url = '<![CDATA['.urlTracking($event, $ad->id, $flightWebsiteID, $publisherAdZoneID, $checksum, '', $isOverReport ).']]>';
                           $xml_file = str_replace($check_key, $url, $xml_file); 
                        }
                    } 
                    
                    if(strpos($xml_file, '[yomedia_third_party_url]') !== FALSE){
                        $thirdTrackingEvents =  json_decode($ad->third_party_tracking);
                
                        if(!empty($thirdTrackingEvents)){
                            $tag = '';
                            foreach($thirdTrackingEvents as $trackingEvent){
                                if(!empty($trackingEvent->event) && !empty($trackingEvent->url)){
                                    $tag .= "<Tracking event=\"".$trackingEvent->event."\"><![CDATA[".$this->replaceParam($trackingEvent->url)."]]></Tracking>";
                                }
                            }
                            $xml_file = str_replace('<Tracking>[yomedia_third_party_url]</Tracking>', $tag, $xml_file);
                        }
                    }
                    
                    if(strpos($xml_file, '[yomedia_click_url]') !== FALSE){
                       $url = '<![CDATA['.urlTracking('click', $ad->id, $flightWebsiteID, $publisherAdZoneID, $checksum, $ad->destination_url, $isOverReport ).']]>';
                       $xml_file = str_replace('[yomedia_click_url]', $url, $xml_file); 
                    }
                    
                    if(strpos($xml_file, '[yomedia_third_impression_url]') !== FALSE){
                        if ($ad->third_impression_track != '') {
                            $thirdImpressionTrackArr = explode("\n", $ad->third_impression_track);
                            if (!empty($thirdImpressionTrackArr)) {
                                $tag = '';
                                foreach ($thirdImpressionTrackArr as $item) {
                                    $tag .= '<Impression><![CDATA['.$this->replaceParam($item).']]></Impression>';
                                }
                                $xml_file = str_replace('<Impression>[yomedia_third_impression_url]</Impression>', $tag, $xml_file);
                            }
                        }
                    }
                    
                    if(strpos($xml_file, '[yomedia_third_click_url]') !== FALSE){
                        $tag = '';
                        if ($ad->third_click_track != '') {
                            $thirdClickTrackArr = explode("\n", $ad->third_click_track);
                            if (!empty($thirdClickTrackArr)) {
                                foreach ($thirdClickTrackArr as $item) {
                                    $tag .= "<ClickTracking><![CDATA[".$this->replaceParam($item)."]]></ClickTracking>";
                                }
                                $xml_file = str_replace('<ClickTracking>[yomedia_third_click_url]</ClickTracking>', $tag, $xml_file);
                            }
                        }
                    }
                    
                    if(strpos($xml_file, '[yomedia_ad_id]') !== FALSE){
                        $xml_file = str_replace('[yomedia_ad_id]', $ad->id, $xml_file);
                    }
                    
                    if(strpos($xml_file, '[yomedia_ga_campaign_url]') !== FALSE){
                        $trackingModel = new Tracking;
                        $deliverModel = new Delivery();
                        $adFormat = $deliverModel->getAdFormat($ad->ad_format_id);
                        $ad_format_name = isset($adFormat->name) ? str_replace(' ', '_', $adFormat->name) : '';     
                        $name = isset($ad->name)? str_replace(' ', '_', $ad->name) : '';
                        $publisher_domain = $trackingModel->getRequestReferer();
                        $campaignInfo = $deliverModel->getCamPaignInfoByPublisher($publisherAdZoneID, $flightWebsiteID, $ad->ad_format_id);
                            
                        if ($campaignInfo) {
                            $flight_name = isset($campaignInfo['flight_name']) ? $campaignInfo['flight_name'] : '';
                            $category_name = isset($campaignInfo['category_name']) ? $campaignInfo['category_name'] : '';
                        }
                        $url = '<![CDATA[http://static.yomedia.vn/campaigns.html?utm_medium='.$ad_format_name.'&utm_content='.$name.'&utm_campaign='.$flight_name.'&utm_term=Video&utm_source='.$publisher_domain.'&rd='.str_random(40).']]>';
                        $xml_file = str_replace('[yomedia_ga_campaign_url]', $url, $xml_file);
                    }
                }
                
                $xml = simplexml_load_string($xml_file);
                if (!$xml) {
                    return response('<VAST version="2.0"></VAST>', 200, $header);
                }            
                
                return response($xml->asXML(), 200, $header);
            } catch (\Exception $e) {
                pr($e);
                return response('<VAST version="2.0"></VAST>', 200, $header);
            }
		}
    	
		return response('<VAST version="2.0"></VAST>', 200, $header);
	}
	
    public function getVastTag()
	{
		$vastTag = Input::get('vast_tag');
		$skip = Input::get('skip', 0);
		$adID = Input::get('aid', 0);
		
	    $hostReferer = '*';
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
            $hostReferer = parse_url($url, PHP_URL_SCHEME) . '://' . getWebDomain($url);
        }
		
		$header['Content-Type']                     = 'application/xml';
        $header['Access-Control-Allow-Origin']      = $hostReferer;
        $header['Access-Control-Allow-Credentials'] = 'true';
        $header['Cache-Control']                    = 'no-store, no-cache, must-revalidate, max-age=0';
        $header['Cache-Control']                    = 'post-check=0, pre-check=0';
        $header['Pragma']                           = 'no-cache';
        
        
		if (!empty($vastTag)) {
            try {
	            $xmlVastTag = $this->getVastAdTagUri($adID, urldecode($vastTag));
                
                if(!empty($xmlVastTag)) {  
                    if(!empty($skip)){
                        $skipoffset= '<Linear '. 'skipoffset="00:00:' . sprintf('%02d', $skip) . '"' . '>';
                        $xmlVastTag = str_replace('<Linear>', $skipoffset, $xmlVastTag);
                    }
                    
                    $xml = simplexml_load_string($xmlVastTag);
                    if (!$xml) {
                        return response('<VAST version="2.0"></VAST>', 200, $header);
                    }            
                    
                    return response($xml->asXML(), 200, $header);
                }   
            } catch (\Exception $e) {
                pr($e);
                return response('<VAST version="2.0"></VAST>', 200, $header);
            }
		}
    	
		return response('<VAST version="2.0"></VAST>', 200, $header);
	}
	
    public function replaceParam($url) {
        $url = str_replace('[timestamp]', time(),$url);
        
        $trackingModel   = new Tracking;
        $hostReferer = $trackingModel->getRequestReferer();
        $url = str_replace('[sitename]', getSiteName($hostReferer),$url);
        $hostReferer = !empty($_SERVER['HTTP_REFERER']) ? urlencode($_SERVER['HTTP_REFERER']) : '';
        $url = str_replace('[yomedia_referer]', $hostReferer,$url);
        return $url;
    }
    
    function getVastAdTagUri($adID, $url) {
        try {
            $redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_5', '6379'), false);
            $cacheKey = "VASTAdTagURI_{$adID}";
            $xmlVastTag = $redis->get($cacheKey);
            if(empty($xmlVastTag)) {
                $xmlVastTag = $this->curlGetContents($url);
                if(strpos($xmlVastTag, '<MediaFiles>') !== FALSE && strpos($xmlVastTag, '</MediaFiles>') !== FALSE){
                    $redis->set($cacheKey, $xmlVastTag, 1);
                }
            }
            
            return $xmlVastTag;
        } catch (\Exception $e) {
            pr($e);
            return FALSE;
        }
    }
    
    function curlGetContents($url){
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
    }

    public function getApiAd(){
	    $this->layout   = null;
		$response       = null;
		$responseType   = '';
		$deliveryStatus = '';

		$requestType     = Input::get('rt', Delivery::REQUEST_TYPE_AD);
		$flightWebsiteID = Input::get('fpid', '');
		$zoneID          = Input::get('zid', 0);
		
		$data            = Input::all();
		$trackingModel   = new Tracking;
		$deliveryModel   = new Delivery;
		$isOverReport = $data['ovr'] = false;
		$showBanner = showBanner();
		if ($showBanner !== FALSE) {
		    $flightWebsiteID = $showBanner;
		}

		$responseData = [];

		$hostReferer = $trackingModel->getRequestReferer();
		$responseType = $trackingModel->checkPreProcess($requestType, $hostReferer, $zoneID);
		//read redis 1
		$data['ref'] = $hostReferer;
		$adZone = $deliveryModel->getAdzone($zoneID);
		if($adZone){
			$platform = '';
			$flightWebsites = $deliveryModel->getAvailableAds($adZone->publisher_site_id, $adZone->ad_format_id, $flightWebsiteID, $platform);
			
			if($flightWebsites){								
				//sort available flights base on priority and retargeting
				//TO DO retargeting
				$flightWebsites = $deliveryModel->sortAvailableFlightWebsites($flightWebsites);
				//lấy ad từ list thỏa điều kiện để trả về
				$deliveryInfo = $deliveryModel->getFullFlightInfo($flightWebsites, $adZone->publisher_site_id, $adZone->ad_format_id);
				$redis = new RedisBaseModel(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT_6', '6379'), false);
				foreach ($flightWebsites as $k => $flightWebsite) {
					if(!empty($flightWebsite) && !empty($deliveryInfo['flightDates'][$flightWebsite->flight_id]) && !empty($deliveryInfo['flights'][$flightWebsite->flight_id])){

						$flightDates = $deliveryInfo['flightDates'][$flightWebsite->flight_id];
						$flight      = $deliveryInfo['flights'][$flightWebsite->flight_id];
						$ad          = $deliveryInfo['ads'][$flight->ad_id];
						$arrPlatform = json_decode($ad->platform);
						if (!empty($arrPlatform) && in_array('mobile_app', $arrPlatform) || isLocal()) {
							$checkFlightDate = $deliveryModel->checkFlightDate($flightDates, $flight);
							//flight date ok
							if($checkFlightDate){
								$deliveryStatus = $deliveryModel->deliveryAd($ad, $flightWebsite, $flight, $flightDates);
								if($deliveryStatus == Delivery::DELIVERY_STATUS_OK || $deliveryStatus == Delivery::DELIVERY_STATUS_OVER_REPORT){
								    //Check retargeting
						        	if (!empty($flight->audience)) {
						        		$check = false;
					        			$audience = json_decode($flight->audience, true);
					        			if (!empty ($audience['audience_id'])) {
				        					if (isset($_COOKIE["yoAu_{$audience['audience_id']}"]) && !empty($_COOKIE["uuid"])) {
				        						if ($_COOKIE["yoAu_{$audience['audience_id']}"] === '1' || substr($_COOKIE["yoAu_{$audience['audience_id']}"], 0, 2) === '1.'){
				        							$check = true;						        					
				        						}								        						
					        				}
					        				if ($audience['operator'] === 'not in') {
				        						$check = !$check;
				        					}
				        				}				        				
				        				if ($check === false) {
				        					$deliveryStatus == Delivery::RESPONSE_TYPE_AUDIENCE_LIMIT;
				        					continue;
				        				}
						        	}
									//trả về ad này
									$serveAd      = $ad;
									$data['aid']  = $ad->id;
									$data['fpid'] = $flightWebsite->id;
									//over report
									if($deliveryStatus == Delivery::DELIVERY_STATUS_OVER_REPORT){
										$data['ovr'] = $isOverReport = true;
									}
									$responseType = Delivery::RESPONSE_TYPE_ADS_SUCCESS;									    
									break;
								}
							}
							else{
								$deliveryStatus = Delivery::RESPONSE_TYPE_FLIGHTDATE_NOT_AVAILABLE;
							}
						} else {
						    $deliveryStatus = Delivery::PLATFORM_TYPE_INVALID;
						}
					}
				}
			}
			if($responseType != Delivery::RESPONSE_TYPE_ADS_SUCCESS){
				$responseType = Delivery::RESPONSE_TYPE_NOT_AVAILABLE;
			}
		}

		if(empty($responseType)) {
			$responseType = Delivery::RESPONSE_TYPE_INVALID;
		} elseif($responseType == Delivery::RESPONSE_TYPE_ADS_SUCCESS){
			(new RawTrackingSummary())->addSummary('ads_request', $data['fpid'], $adZone->id, $adZone->ad_format_id, $flightWebsite->flight_id, $flightWebsite->id, $flight->ad_id, $flight->campaign_id, $flightWebsite->publisher_base_cost, $isOverReport);
			if(!empty($serveAd)){
				pr($serveAd);
				$responseData['w'] = intval($serveAd->width);
				$responseData['h'] = intval($serveAd->height);
				$responseData['mime'] = !empty($serveAd->mime) ? $serveAd->mime : '';
				if ($serveAd->ad_type === 'html') {
					$responseData['adm'] = urlencode($serveAd->html_source);
				} else {
					$responseData['adm'] = urlencode($serveAd->source_url);
				}
				$responseData['pos'] = !empty($serveAd->position) ? $serveAd->position : '';
				$arrTrackingImpression = [];
				$arrTrackingImpression[] = urlencode(urlTracking('impression', $data['aid'], $data['fpid'], $zoneID, '', '', $data['ovr'], '') . '&plf=mobile_app');
				if (!empty($serveAd->third_impression_track)) {
		            $thirdImpressionTrackArr = explode("\n", $serveAd->third_impression_track);
		            if (!empty($thirdImpressionTrackArr)) {
		                foreach ($thirdImpressionTrackArr as $item) {
		                    $arrTrackingImpression[] = urlencode($this->replaceParam($item));
		                }
		            }
		        }
	            $responseData['nimp'] = $arrTrackingImpression;

	            $arrTrackingClick = [];

				$arrTrackingClick[] = urlencode(urlTracking('click', $data['aid'], $data['fpid'], $zoneID, '', '', $data['ovr'], '') . '&plf=mobile_app');
				if (!empty($serveAd->third_click_track)) {
		            $thirdClickTrackArr = explode("\n", $serveAd->third_click_track);
		            if (!empty($thirdClickTrackArr)) {
		                foreach ($thirdClickTrackArr as $item) {
		                    $arrTrackingClick[] = urlencode($this->replaceParam($item));
		                }
		            }
		        }
	            $responseData['nclk'] = $arrTrackingClick;
	            $responseData['lpage'] = urlencode($this->replaceParam($serveAd->destination_url));
	            $responseData['xml'] = null;
			}
		}

		pr($responseData);
		pr($deliveryStatus);
		pr($responseType,1);

		return response()->json($responseData);
	}
}