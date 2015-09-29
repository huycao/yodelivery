<?php
    $wrapperAds = "YoMediaBanner_{$data['zid']}";
    $elAds = "YoMediaBanner_{$data['zid']}_Banner";
    $elWidth = $data['ad']->width;
    $elHeight = $data['ad']->height;
    $ad_format = 'standard_banner';

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

function showYoMediaBannerAd_{{$data['zid']}}(s) {
    var eWidth = parseInt('{{$elWidth}}');
    var eHeight = parseInt('{{$elHeight}}');


    var ff = '{{$data['ad']->source_url}}';
    var impressionTrack = encodeURIComponent("{{URL::to('/')}}/track?evt=impression&aid={{$data['aid']}}&fpid={{$data['fpid']}}&zid={{$data['zid']}}&rt=1&cs={{$data['checksum']}}");
    <?php if(!empty($thirdImpressionTrackArr)){ ?>
        <?php foreach( $thirdImpressionTrackArr as $item ){ ?>
            impressionTrack += '|'+encodeURIComponent("{{trim($item)}}");
        <?php } ?>
    <?php } ?>

    
    var clickTag = encodeURIComponent("{{URL::to('/')}}/track?evt=click&aid={{$data['aid']}}&fpid={{$data['fpid']}}&zid={{$data['zid']}}&rt=1&to={{urlencode($data['ad']->destination_url)}}&cs={{$data['checksum']}}");
    var clickTrack= "";

    <?php if(!empty($thirdClickTrackArr)){ ?>
        <?php $count = 0; ?>
        <?php foreach( $thirdClickTrackArr as $item ){ ?>
            <?php $count++; ?>
            <?php if( $count == 1 ){ ?>
                clickTrack += encodeURIComponent("{{trim($item)}}");
            <?php }else{ ?>
                clickTrack += '|'+encodeURIComponent("{{trim($item)}}");
            <?php } ?>
        <?php } ?>
    <?php } ?>
    
    var flashvar = {
        zid : {{ $data['zid'] }},
        {{-- impression : impressionTrack, --}}
        clickTrack : clickTrack,
        clickUrl : clickTag
    }

    avlInteractModule.addBanners(
    	'{{$ad_format}}',
    	parseInt('{{$data['zid']}}'),
    	eWidth,
    	eHeight,
    	ff,
    	clickTag,
    	impressionTrack,
    	clickTrack,
    	flashvar
    );
    avlHelperModule.embedTracking("{{ TRACKER_URL }}track?evt=impression&aid={{$data['aid']}}&fpid={{$data['fpid']}}&zid={{$data['zid']}}&rt=1&cs={{$data['checksum']}}{{ !empty($data['ovr']) ? "&ovr=1" : '' }}");
    <?php if(!empty($thirdClickTrackArr)){ ?>
        <?php foreach( $thirdClickTrackArr as $item ){ ?>
            avlHelperModule.embedTracking("{{trim(str_replace('[timestamp]', time(), $item))}}");
        <?php } ?>
    <?php } ?>
}

window.onclick = function(event) {
	var checkID = 'yomedia-destination-{{$data['zid']}}';
	
	if (checkID == event.target.id) {
    	<?php if(!empty($thirdImpressionTrackArr)){ ?>
            <?php foreach( $thirdImpressionTrackArr as $item ){ ?>
                avlHelperModule.embedTracking("{{trim(str_replace('[timestamp]', time(), $item))}}");
            <?php } ?>
        <?php } ?>
    }
}

showYoMediaBannerAd_{{$data['zid']}}(1);