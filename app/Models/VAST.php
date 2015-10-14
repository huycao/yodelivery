<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\Response;

class VAST extends Eloquent {
    const VAST_INLINE = 'inline';
    const VAST_WRAPPER = 'wrapper';

    protected $append = array('impTracksTag','linearTracks','nonLinearTracks','durationText', 'creativeType','trackClick');
    protected $header;
    public function getImpTracksTagAttribute(){
        // không track impression đối với các format khác video
        if($this->ad_format != 8){
            return '';
        }
        $tag = '';
        $tag .= '<Impression><![CDATA['.urlTracking('impression', $this->id, $this->flight_publisher_id, $this->publisher_ad_zone_id, $this->checksum, '', $this->ovr, $this->referrer  ).']]></Impression>';
        if ($this->third_impression_track != '') {
            $thirdImpressionTrackArr = explode("\n", $this->third_impression_track);
            if (!empty($thirdImpressionTrackArr)) {
                foreach ($thirdImpressionTrackArr as $item) {
                    $tag .= '<Impression><![CDATA['.$this->replaceParam($item).']]></Impression>';
                }
            }
        }
        
        $tag .= '<Impression><![CDATA[http://static.yomedia.vn/analytics.html?utm_campaign=Yomedia&utm_source='.$this->category_name.'&utm_medium='.$this->publisher_domain.'&utm_content='.$this->ad_format_name.'&rd='.str_random(40).']]></Impression>';
        $tag .= '<Impression><![CDATA[http://static.yomedia.vn/campaigns.html?utm_medium='.$this->ad_format_name.'&utm_content='.$this->name.'&utm_campaign='.$this->flight_name.'&utm_term=Video&utm_source='.$this->publisher_domain.'&rd='.str_random(40).']]></Impression>';
        
        return $tag;
    }

    public function getLinearTracksAttribute(){
        $tag = "<TrackingEvents>";
        $TrackingEvents = array('start','firstQuartile','midpoint','thirdQuartile','complete', 'mute', 'unmute', 'pause', 'fullscreen');
        foreach ($TrackingEvents as $event){
            $tag .= "<Tracking event=\"".$event."\"><![CDATA[".urlTracking($event, $this->id, $this->flight_publisher_id, $this->publisher_ad_zone_id, $this->checksum, '', $this->ovr, $this->referrer )."]]></Tracking>";
        }
        
        $thirdTrackingEvents =  json_decode($this->third_party_tracking);
        
        if(!empty($thirdTrackingEvents)){
            foreach($thirdTrackingEvents as $trackingEvent){
                if(!empty($trackingEvent->event) && !empty($trackingEvent->url)){
                    $tag .= "<Tracking event=\"".$trackingEvent->event."\"><![CDATA[".$this->replaceParam($trackingEvent->url)."]]></Tracking>";
                }
            }
        }
        
        $tag .= '</TrackingEvents>';
        return $tag;
    }

    public function getNonLinearTracksAttribute(){

        $tag = '<TrackingEvents>';
        $TrackingEvents = array('start','firstQuartile','midpoint','thirdQuartile','complete');
        foreach ($TrackingEvents as $event){
            $tag .= '<Tracking event="'.$event.'"><![CDATA['.urlTracking($event, $this->id, $this->flight_publisher_id, $this->publisher_ad_zone_id , $this->checksum, '', $this->ovr, $this->referrer ).']]></Tracking>';
        }
        
        $thirdTrackingEvents =  json_decode($this->third_party_tracking);
        
        if(!empty($thirdTrackingEvents)){
            foreach($thirdTrackingEvents as $trackingEvent){
                $tag .= "<Tracking event=\"".$trackingEvent->event."\"><![CDATA[".$this->replaceParam($trackingEvent->url)."]]></Tracking>";
            }
        }
        $tag .= '</TrackingEvents>';
        return $tag;
    }

    public function getTrackClickAttribute(){
        return urlTracking('click', $this->id, $this->flight_publisher_id, $this->publisher_ad_zone_id, $this->checksum, $this->url, $this->ovr, $this->referrer);
    }
    
    public function getTrackClick3rdAttribute(){
        $tag = '';
        if ($this->third_click_track != '') {
            $thirdClickTrackArr = explode("\n", $this->third_click_track);
            if (!empty($thirdClickTrackArr)) {
                foreach ($thirdClickTrackArr as $item) {
                    $tag .= "<ClickTracking><![CDATA[".$this->replaceParam($item)."]]></ClickTracking>";
                }
            }
        }
        return $tag;
    }

    public function getDurationTextAttribute(){
        return secsToDuration($this->duration);
    }

    public function getCreativeTypeAttribute(){
        $array_source = explode('.', trim($this->file));
        $ext = end($array_source);
        $creativeType = '';
        switch ($ext) {
            case 'flv':
                $creativeType = 'video/x-flv';
                break;
            case 'mp4':
                $creativeType = 'video/mp4';
                break;
            case 'swf':
                $creativeType = 'application/x-shockwave-flash';break;
            case 'png':
                $creativeType = 'image/png';break;
            case 'jpg':
                $creativeType = 'image/jpeg';break;
            case 'gif':
                $creativeType = 'image/gif';break;
        }
        return $creativeType;
    }

    function makeVAST($adID, $flightPublisherID, $publisherAdZoneID, $checksum = '', $isOverReport = false, $referrer = ''){
        // get ad data
        $deliveryModel   = new Delivery;
        $ad  = $deliveryModel->getAd($adID);
        
        $adZone = $deliveryModel->getAdzone($publisherAdZoneID);
        if($adZone){
            $flightWebsite = $deliveryModel->getFullFlightWebsite($flightPublisherID, $adZone->publisher_site_id, $adZone->ad_format_id, '');
            if ($flightWebsite) {
                $rawTrackingSummary = new RawTrackingSummary();
                $rawTrackingSummary->addSummary('ads_request', $flightWebsite->website_id, $adZone->id, $adZone->ad_format_id, $flightWebsite->flight_id, $flightWebsite->id, $flightWebsite->flight->ad_id, $flightWebsite->flight->campaign_id, $flightWebsite->publisher_base_cost, $isOverReport);
            }
            
        }
        if($ad && $flightPublisherID && $publisherAdZoneID){
            $XMLView                    = 'none';
            $this->initVast($ad, $flightPublisherID, $publisherAdZoneID, $checksum, $isOverReport, $referrer);
            if(!empty($this->id)){
                if($this->type_vast == self::VAST_INLINE){
                    $XMLView = 'inline';
                }
                else{
                    $XMLView = 'wrapper';
                }
            }
            $this->setHeaderVast();
            \View::addLocation(base_path() .'/resources/views/vast');
            $body = \View::make($XMLView)->with('ad',$this);
            return response($body, 200, $this->header);
        }
        else{
            return $this->makeEmptyVast();
        }
        
    }

    function makeBackupVast($zoneId, $wrapperTag){
        $ad              = new StdClass();
        $ad->id          = $zoneID;
        $ad->wrapper_tag = $wrapperTag;
        $ad->isBackupAd  = true;
        \View::addLocation(base_path() .'/resources/views/vast');
        $body = View::make('wrapper')->with('ad',$ad);
        $this->setHeaderVast();
        return response($body, 200, $this->header);
    }

    public function initVast($ad, $flightPublisherID, $publisherAdZoneID, $checksum, $isOverReport = false, $referrer = ''){
        if($ad && $flightPublisherID && $publisherAdZoneID){
            $trackingModel = new Tracking;
            $deliverModel = new Delivery();
            $this->id                     = $ad->id;
            $this->flight_publisher_id    = $flightPublisherID;
            $this->publisher_ad_zone_id   = $publisherAdZoneID;
            $this->linear                 = $ad->video_linear;
            $this->width                  = $ad->width;
            $this->height                 = $ad->height;
            $this->duration               = $ad->video_duration;
            $this->type_vast              = $ad->video_type_vast;
            //$this->vast_version           = $ad->vast_version;
            $this->skipads                = $ad->skipads;
            $this->file                   = $ad->source_url;
            $this->title                  = $ad->name;
            
            if (empty($ad->vast_include)) {
                $this->wrapper_tag        = $this->replaceParam($ad->video_wrapper_tag);
            } else {
                $vastTagUrl = urlencode($this->replaceParam($ad->video_wrapper_tag));
                $this->wrapper_tag        = AD_SERVER_FILE . 'get-vast-tag?vast_tag=' . $vastTagUrl . '&skip=' . $ad->skipads . '&aid=' . $ad->id;
            }
            $this->bitrate                = $ad->video_bitrate;
            $this->url                    = $ad->destination_url;
            $this->ad_format              = $ad->ad_format_id;
            $this->referrer               = $referrer;
            $this->third_party_tracking   = $ad->third_party_tracking;
            $this->third_impression_track = $ad->third_impression_track;
            $this->third_click_track      = $ad->third_click_track;
            $this->checksum               = $checksum;
            $this->ovr                    = $isOverReport;
            $adFormat = $deliverModel->getAdFormat($ad->ad_format_id);
            $this->ad_format_name         = isset($adFormat->name) ? str_replace(' ', '_', $adFormat->name): '';     
            $this->name                   = isset($ad->name)? str_replace(' ', '', strtolower($ad->name)) : '';
            $this->publisher_domain       = $trackingModel->getRequestReferer();
            $campaignInfo = $deliverModel->getCamPaignInfoByPublisher($publisherAdZoneID, $flightPublisherID, $ad->ad_format_id);
            if ($campaignInfo) {
                $this->flight_name        = isset($campaignInfo['flight_name']) ? $campaignInfo['flight_name'] : '';
                $this->category_name      = isset($campaignInfo['category_name']) ? $campaignInfo['category_name'] : '';
            }
            
            return true;
        }
        return false;
    }

    public function makeEmptyVast(){
        $body                    = '<VAST version="2.0"/>';
        $this->setHeaderVast();
        return response($body, 200, $this->header);
    }

    public function setHeaderVast(){
        $hostReferer = '*';
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
            $hostReferer = parse_url($url, PHP_URL_SCHEME) . '://' . getWebDomain($url);
        }
        
        $this->header['Content-Type']                     = 'application/xml';
        $this->header['Access-Control-Allow-Origin']      = $hostReferer;
        $this->header['Access-Control-Allow-Credentials'] = 'true';
        $this->header['Cache-Control']                    = 'no-store, no-cache, must-revalidate, max-age=0';
        $this->header['Cache-Control']                    = 'post-check=0, pre-check=0';
        $this->header['Pragma']                           = 'no-cache';
        return true;
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

}