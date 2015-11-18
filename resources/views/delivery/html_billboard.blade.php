@include("footer")
<?php
    $wrapperAds = 'YoMediaBalloon';
    $elAds = "YoMediaBalloon_".$data['zid'];
    $eidtype         = substr($data['element_id'],0,1);
    $eid             = substr($data['element_id'],1);
    $htmlSource = preg_replace('/\s\s+/', '', $data['ad']->html_source);
    $htmlSource = str_replace('[yomedia_maincontain]', $eid, $htmlSource);
    $htmlSource = str_replace('[yomedia_zone_id]', $data['zid'], $htmlSource);
    $displayType = isset($data['ad']->display_type) ? $data['ad']->display_type : '';
    if( !empty( $data['ad']->third_impression_track ) ){
        $thirdImpressionTrackArr = explode("\n", $data['ad']->third_impression_track);
    }else{
        $thirdImpressionTrackArr = [];
    }

    if( !empty( $data['ad']->third_click_track ) ){
        $thirdClickTrackArr = explode("\n", $data['ad']->third_click_track);
    }else{
        $thirdClickTrackArr = [];
    }

    $trackUrl = AD_SERVER_FILE;
    $destinationUrl = $data['ad']->destination_url;
    $destinationUrlEnCode = urlencode($destinationUrl);
    $ovr = !empty($data['ovr']) ? "&ovr=1" : '';
    $clickTag = "{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}";
    $clickTagEnCode = urlencode("{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}");
    $htmlSource = trim(str_replace('[yomedia_click_url]', $clickTag, $htmlSource));
    $htmlSource = trim(str_replace('[yomedia_click_url_encode]', $clickTagEnCode, $htmlSource));
    
    if(!empty($thirdClickTrackArr)){
        $count = 0;
        $clickTrackEnCode = '';
        foreach( $thirdClickTrackArr as $item ){
            $count++;
            if($count == 1){
                $clickTrackEnCode .= urlencode(trim(str_replace('[timestamp]', time(), $item)));
            } else {
                $clickTrackEnCode .= '|' . urlencode(trim(str_replace('[timestamp]', time(), $item)));
            }
        }
        
        $htmlSource = str_replace('[yomedia_third_click_url_encode]', $clickTrackEnCode, $htmlSource);
    }    
?>
var bannerID = 'YoMediaBanner_{!! $data['zid'] !!}';
@if ('video' != $data['ad']->ad_type)
	avlInteractModule.innerHTMLAds('{!! $data['zid'] !!}', '{!! addslashes($htmlSource) !!}', '{!! $displayType !!}');
    avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!!  !empty($data['ovr']) ? "&ovr=1" : ''  !!}");
    <?php $data['effect'] = 'HTML';?>
    @include('ga_campaign')
    @if(!empty($thirdImpressionTrackArr))
        @foreach( $thirdImpressionTrackArr as $item )
            avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
        @endforeach
    @endif
    if (typeof _YoImp != 'undefined' && avlHelperModule.validateUrl(_YoImp)) {
    	avlHelperModule.embedTracking(_YoImp);
    }
    
    <?php
        $eventArr = [
            'start','firstQuartile','midpoint','thirdQuartile','complete'
        ];
        $thirdPartyTrackings = [];
        $data['ad']->third_party_tracking = json_decode($data['ad']->third_party_tracking, 1);
        if(!empty($data['ad']->third_party_tracking) && is_array($data['ad']->third_party_tracking) && count($data['ad']->third_party_tracking)){
            foreach ($data['ad']->third_party_tracking as $tracker) {
                $thirdPartyTrackings[$tracker['event']] = $tracker['url'];
            }
        }
    ?>
    @foreach ($eventArr as $event)
    function {!! $event !!}YomediaVideo_{{ $data['zid'] }}(){
        avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt={!! $event !!}&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!!  !empty($data['ovr']) ? "&ovr=1" : ''  !!}");
        @if(!empty($thirdPartyTrackings[$event]))
        	avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $thirdPartyTrackings[$event])) !!}");
        @endif
    }
    @endforeach
@else
	<?php 
	    $xmlUrl = "{$trackUrl}render-vast?aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&cs={$data['checksum']}{$ovr}";
	    $htmlSource = trim(str_replace('[yomedia_vast_url]', $xmlUrl, $htmlSource));
    ?>
    avlInteractModule.innerHTMLAds('{!! $data['zid'] !!}', '{!! addslashes($htmlSource) !!}', '{!! $displayType !!}');
@endif
function minYoMediaPopupAd_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectAd('YoMediaBanner', 'top-down', sPos, 'min', preExpandWidth, preExpandHeight, expandWidth, expandHeight, barHeight);
}

function restoreYoMediaPopupAd_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectAd('YoMediaBanner', 'top-down', sPos, 'max', preExpandWidth, preExpandHeight, expandWidth, expandHeight, barHeight);
}

function setYoMediaExpand_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectExpand('YoMediaBanner', 'ex', sPos, preExpandWidth, preExpandHeight, expandWidth, expandHeight);
}

function setYoMediaPre_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectExpand('YoMediaBanner', 'top-down', sPos, preExpandWidth, preExpandHeight, expandWidth, expandHeight, barHeight);
}
// Close popupad
function closeYoMediaPopupAd_{!! $data['zid'] !!}() {
    var d = document.getElementById('YoMediaBanner');
	easingYomedia_{!! $data['zid'] !!}(d, 0);
    var d = document.getElementById('YoMediaBanner_{!! $data['zid'] !!}');
    d.style.display = 'none';
}

function easingYomedia_{!! $data['zid'] !!}(e, n) {
	var pos = parseInt(e.style.height);
	var change = n - pos;
	var total = change > 0 ? pos + Math.ceil((change / 2)) : pos + Math.floor((change / 2));
	e.style.height = total + "px";
	function r() {
		easingYomedia_{!! $data['zid'] !!}(e, n);
	}
	
	n = n > 0 ? Math.ceil(n) : Math.floor(n);
	if(change == 0) {
		clearTimeout(timer);
		return;
	}
	timer = setTimeout(r, 100);
}
// Click tracking
function clickTrackingYomedia_{!! $data['zid'] !!}() {
    @include("ga_click")
	var clickTag = '{!! $clickTag !!}';
	@if (!empty($clickTrackEnCode))
	var clickTrack = '{!! $clickTrackEnCode !!}';
	var clickTrackingList = clickTrack.split('|');
	clickTrackingList.forEach(function(item) {
		avlHelperModule.embedTracking(decodeURIComponent(item));
	});
	@endif
	if (typeof _YoClick != 'undefined' && avlHelperModule.validateUrl(_YoClick)) {
    	avlHelperModule.embedTracking(_YoClick);
    }
	window.open(clickTag);
}