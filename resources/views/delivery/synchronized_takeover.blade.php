@include("url_track_ga")
<?php
	if (!empty($data['ad']->destination_url)) {
        $data['ad']->destination_url = trim(str_replace('[timestamp]', time(), $data['ad']->destination_url));
    }
    if( !empty( $data['ad']->third_impression_track ) ){
            $thirdImpressionTrackArr = explode("\n", $data['ad']->third_impression_track);
    } else{
        $thirdImpressionTrackArr = [];   
    }
    
    if ( !empty( $data['ad']->third_click_track ) ){
        $thirdClickTrackArr = explode("\n", $data['ad']->third_click_track);
     }else{
        $thirdClickTrackArr = [];   
    }
    
    $ovr = '';
    if (!empty($data['ovr'])) {
        $ovr = '&ovr=1';
    }
    $trackUrl = TRACKER_URL;
    $destinationUrlEnCode = urlencode($data['ad']->destination_url);
    $clickTag = "{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}";

	$htmlSource = preg_replace('/\s\s+/', '', $data['ad']->html_source);
	$htmlSource = str_replace('[yomedia_zone_id]', $data['zid'], $htmlSource);
	$htmlSource = str_replace('[yomedia_page_width]', $data['pageWidth'], $htmlSource);
?>
document.write('{!! addslashes($htmlSource) !!}');
avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!! $ovr !!}");
<?php $data['effect'] = 'Synchronized_Takeover_Banner';?>
@include('ga_campaign')
@if(!empty($thirdImpressionTrackArr))
    @foreach( $thirdImpressionTrackArr as $item )
        avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
    @endforeach
@endif

if (typeof _YoImp != 'undefined' && avlHelperModule.validateUrl(_YoImp)) {
    avlHelperModule.embedTracking(_YoImp);
}

function showYomediaSyncTO_{!! $data['zid'] !!}(){
	stage1.setFlashVars('call=onBeginBig');
    document.getElementById('YomediaSyncOver_{!! $data['zid'] !!}').style.display = "inline-block";
}

function closeYomediaSyncTO_{!! $data['zid'] !!}(){
    document.getElementById('YomediaSyncOver_{!! $data['zid'] !!}').style.display = "none";
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

@include("footer")