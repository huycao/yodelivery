<?php
    $wrapperAds = 'YoMediaBanner';
    $elAds = "YoMediaBanner_".$data['zid'];
    $elWidth = $data['ad']->width;
    $elHeight = $data['ad']->height;

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


    var flash = '{{$data['ad']->source_url}}';
    var impressionTrack = encodeURIComponent("{{ TRACKER_URL }}track?evt=impression&aid={{$data['aid']}}&fpid={{$data['fpid']}}&zid={{$data['zid']}}&rt=1&cs={{$data['checksum']}}");
<?php if(!empty($thirdImpressionTrackArr)){ ?>
    <?php foreach( $thirdImpressionTrackArr as $item ){ ?>
        impressionTrack += '|'+encodeURIComponent("{{trim($item)}}");
    <?php } ?>
<?php } ?>

    
    var clickTag = encodeURIComponent("{{ TRACKER_URL }}track?evt=click&aid={{$data['aid']}}&fpid={{$data['fpid']}}&zid={{$data['zid']}}&rt=1&to={{urlencode($data['ad']->destination_url)}}&cs={{$data['checksum']}}");
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
    

    var ff = flash+'?impression='+impressionTrack+"&clickTrack="+clickTrack+"&clickTag="+clickTag;
    

    avlInteractModule.initBanner(
    	'{{$wrapperAds}}',
    	'{{$elAds}}',
    	eWidth,
    	eHeight,
    	ff,
    	parseInt('{{$data['zid']}}'),
    	'{{$data['ad']->flash_wmode}}'
    );
}

showYoMediaBannerAd_{{$data['zid']}}(1);