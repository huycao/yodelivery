<?php
    $wrapperAds = "YoMediaCatfish_".$data['zid'];
    $elAds = "YoMediaCatfishContent_".$data['zid'];
    $expandWidth = $data['ad']->width > 0 ? $data['ad']->width : 0;
    $expandHeight = $data['ad']->height > 0 ? $data['ad']->height : 0;
    $preExpandWidth  = $data['ad']->width_2 > 0 ? $data['ad']->width_2 : 0;
    $preExpandHeight = $data['ad']->height_2 > 0 ? $data['ad']->height_2 : 0;
    //Bar height
    $barHeight= !empty($data['ad']->bar_height) ? $data['ad']->bar_height : 50;
    $destination_url = $data['ad']->destination_url;
    $source          = !empty($data['ad']->source_url) ? $data['ad']->source_url : '';
    
    if (!empty($data['ad']->destination_url)) {
        $data['ad']->destination_url = trim(str_replace('[timestamp]', time(), $data['ad']->destination_url));
    }
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
    
    $ovr = '';
    if (!empty($data['ovr'])) {
        $ovr = '&ovr=1';
    }
    $trackUrl = TRACKER_URL;
    $impressionUrl = "{$trackUrl}track?evt=impression&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&cs={$data['checksum']}{$ovr}";
    $destinationUrlEnCode = urlencode($data['ad']->destination_url);
    $clickTag = "{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}";
?>
avlHelperModule.loadAvlStyle();

function showYoMediaPopupAd_{!! $data['zid'] !!}(s) {
    renderBannerYomedia_{!! $data['zid'] !!}('{!! $wrapperAds !!}', '{!! $elAds !!}', '{!! $source !!}', {!! $expandWidth !!}, {!! $expandHeight !!});
    
    avlHelperModule.embedTracking("{!! $impressionUrl !!}");
    
    @if(!empty($thirdImpressionTrackArr))
        @foreach( $thirdImpressionTrackArr as $item )
            avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
        @endforeach
    @endif
    if (typeof _YoImp != 'undefined' && avlHelperModule.validateUrl(_YoImp)) {
    	avlHelperModule.embedTracking(_YoImp);
    }
    <?php $data['effect'] = 'WapCatfish'; ?>
    @include('ga_campaign')
}

function renderBannerYomedia_{!! $data['zid'] !!}(elCatfish, elCatfishContent, ads, width, height) {
	if (domManipulate.getElid(elCatfish)){
		domManipulate.remove(elCatfish);
	}
	var catfish = domManipulate.create('div', elCatfish);
    domManipulate.append(catfish);
    
    var catfishContent = '<div id="'+elCatfishContent+'" style="position:fixed; bottom:0px; left:0px; margin: 0; padding: 0; clear: both; width: 100%; z-index: 9999;">';
    catfishContent += '<div id="YomediaCatfishBg_{!! $data['zid'] !!}" style="bottom: 0px; background: #000; width: 100%; clear: both; padding: 0; text-align: center; height: '+height+'px;">';
    catfishContent += '<div style="height:' + height + 'px; width:' + width + 'px; left: 0px; right: 0px; top: 0px; bottom:0px; margin:auto; position: relative;">';
    catfishContent += '<a href="javascript:;" onclick="clickTrackingYomedia_{!! $data['zid'] !!}()" rel="nofollow">';
    catfishContent += '<img style="height:' + height + 'px; width:' + width + 'px;" vspace="0" hspace="0" border="0" id="YomediaAds_{!! $data['zid'] !!}" src="'+ ads +'">';
    catfishContent += '</a>';
    catfishContent += '<img onclick="closeBannerYomedia_{!! $data['zid'] !!}()" style="height:12px; width:12px; position: absolute; top: 0; right: 0; z-index: 99999" src="http://static.yomedia.vn/public/images/btn_close.png">';
    catfishContent += '</div>';    
    catfishContent += '</div>';
    catfishContent += '</div>';
    
    catfish.innerHTML = catfishContent;
}

function closeBannerYomedia_{!! $data['zid'] !!}() {
    if (domManipulate.getElid('{!! $wrapperAds !!}')){
		domManipulate.remove('{!! $wrapperAds !!}');
	}
}

function clickTrackingYomedia_{!! $data['zid'] !!}() {
	var clickTag = '{!! $clickTag !!}';
	@if(!empty($thirdClickTrackArr))
        @foreach( $thirdClickTrackArr as $item )
            @if (!empty($item))
            	avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
        	@endif
        @endforeach
    @endif
    if (typeof _YoClick != 'undefined' && avlHelperModule.validateUrl(_YoClick)) {
    	avlHelperModule.embedTracking(_YoClick);
    }
	window.open(clickTag);
}

document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        showYoMediaPopupAd_{!! $data['zid'] !!}(1);
    }
}

@include("footer")
