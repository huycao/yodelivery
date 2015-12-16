@include("url_track_ga")
<?php
    $wrapperAds = 'YoMediaBanner';
    $elAds = "YoMediaBanner_".$data['zid'];
    $expandWidth = max($data['ad']->width_2, $data['ad']->width);
    $expandHeight = max($data['ad']->height_2, $data['ad']->height);
    $preExpandWidth  = $data['ad']->width_2 > 0 ? min($data['ad']->width_2, $data['ad']->width) : $data['ad']->width;
    $preExpandHeight = $data['ad']->height_2 > 0 ? min($data['ad']->height_2, $data['ad']->height) : $data['ad']->height;
    $eidtype = substr($data['element_id'],0,1);
    $eid = substr($data['element_id'],1);

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
    
?>

avlHelperModule.loadAvlStyle();

function showYoMediaBannerAd_{!! $data['zid'] !!}(s) {
    var eWidth = parseInt('{!! $expandWidth !!}');
    var eHeight = parseInt('{!! $expandHeight !!}');


    var flash = '{!! $data['ad']->source_url !!}';
    var impressionTrack = encodeURIComponent("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}");
<?php if(!empty($thirdImpressionTrackArr)){ ?>
    <?php foreach( $thirdImpressionTrackArr as $item ){ ?>
        impressionTrack += '|'+encodeURIComponent("{!! trim($item) !!}");
    <?php } ?>
<?php } ?>

    
    var clickTag = encodeURIComponent("{!!  TRACKER_URL  !!}track?evt=click&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&to={!! urlencode($data['ad']->destination_url) !!}&cs={!! $data['checksum'] !!}");
    var clickTrack= "";

<?php if(!empty($thirdClickTrackArr)){ ?>
    <?php $count = 0; ?>
    <?php foreach( $thirdClickTrackArr as $item ){ ?>
        <?php $count++; ?>
        <?php if( $count == 1 ){ ?>
            clickTrack += encodeURIComponent("{!! trim($item) !!}");
        <?php }else{ ?>
            clickTrack += '|'+encodeURIComponent("{!! trim($item) !!}");
        <?php } ?>
    <?php } ?>
<?php } ?>
    
    if (typeof _YoClick != 'undefined' && avlHelperModule.validateUrl(_YoClick)) {
    	if ("" != clickTrack) {
    		clickTrack += '|'+encodeURIComponent(_YoClick);
    	} else {
    		clickTrack += encodeURIComponent(_YoClick);
    	}
    }

    var ff = flash;
	var flashvar = {
        zid : {!!  $data['zid']  !!},
        clickTrack : clickTrack,
        clickUrl : clickTag,
        urlVideo : '{!! !empty($data['ad']->source_url2) ? $data['ad']->source_url2 : '' !!}'
    }    

    avlInteractModule.initBanner(
    	'{!! $wrapperAds !!}',
    	'{!! $elAds !!}',
    	eWidth,
    	eHeight,
    	ff,
    	flashvar,
    	'{!! $data['ad']->flash_wmode !!}',
    	'{!! $eid !!}'
    );
    avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!!  !empty($data['ovr']) ? "&ovr=1" : ''  !!}");

    <?php if(!empty($thirdImpressionTrackArr)){ ?>
        <?php foreach( $thirdImpressionTrackArr as $item ){ ?>
            avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
        <?php } ?>
    <?php } ?>
    if (typeof _YoImp != 'undefined' && avlHelperModule.validateUrl(_YoImp)) {
    	avlHelperModule.embedTracking(_YoImp);
    }
    
    <?php $data['effect'] = 'Banner_Inpage';?>
    @include('ga_campaign')
}

function closeYoMediaPopupAd_{!! $data['zid'] !!}() {
	var d = document.getElementById('{!! $wrapperAds !!}');
	easingYomedia_{!! $data['zid'] !!}(d, 31);
    var d = document.getElementById('{!! $elAds !!}');
    d.style.display = 'none';
}


function setYoMediaPre_{!! $data['zid'] !!}() {
	var d = document.getElementById('{!! $elAds !!}');
    d.style.display = 'block';
    var d = document.getElementById('{!! $wrapperAds !!}');
    d.style.cssText = 'position: relative; display: block; margin: auto; width: '+parseInt('{!! $expandWidth !!}')+'px; height: '+parseInt('{!! $expandHeight !!}')+'px; z-index: 214748000;';
    document.getElementById('{!! $elAds !!}_Show_Ad').style.display = 'none';
}

window.onload = function () { 
    document.getElementById('{!! $elAds !!}_Show_Ad').onclick = setYoMediaPre_{!! $data['zid'] !!};
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
		document.getElementById('{!! $elAds !!}_Show_Ad').style.display = 'block';
		return;
	}
	timer = setTimeout(r, 100);
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

showYoMediaBannerAd_{!! $data['zid'] !!}(1);
@include("footer")