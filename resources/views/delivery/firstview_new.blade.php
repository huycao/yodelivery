@include("url_track_ga")
<?php
$wrapperAds = "YoMediaFirstView_{$data['zid']}";
$source =$data['ad']->source_url;
$width = $data['ad']->width;
$height = $data['ad']->height;
$destination_url = $data['ad']->destination_url;
$btn_close = STATIC_URL. 'public/images/close_button.png';
$ovr = '';
if (!empty($data['ovr'])) {
    $ovr = '&ovr=1';
}
$trackUrl = TRACKER_URL;
$destinationUrlEnCode = urlencode($data['ad']->destination_url);
$clickTag = "{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}";
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

?>
    avlHelperModule.loadAvlStyle();
    function showYoMediaPopupAd_{!! $data['zid'] !!}(s) {
        if(avlInteractModule.isMobile() == false) return false;
        var wrapper_{!! $data['zid'] !!} = document.getElementById("{!! $wrapperAds !!}");
        if (wrapper_{!! $data['zid'] !!}) {
            wrapper_{!! $data['zid'] !!}.parentNode.removeChild(wrapper_{!! $data['zid'] !!});
        }
        
        wrapper_{!! $data['zid'] !!} = domManipulate.create('div', '{!! $wrapperAds !!}', 'border: 0px none; z-index: 100000; width: 100%; height: 100%; position: fixed; overflow: hidden;', ''), document.body.insertBefore(wrapper_{!! $data['zid'] !!}, document.body.childNodes[0]);
        var content = '';
        content += '<div id="yomedia-banner-ad-{!! $data['zid'] !!}" style="width: 100%;height: 100%;background-color: rgba(0, 0, 0, 0.8);position: absolute;overflow: hidden;">';
        content += '<div id="yomedia-banner-close" onclick="closeYoMediaPopupAd_{!! $data['zid'] !!}();" style="width: 40px;height: 40px;background-image: url(\'{!! $btn_close !!}\');position: absolute;top: 0;right: 0;z-index: 50000;"></div>';
        content += '<div id="yomedia-banner-wrapper" style="width: {!! $width !!}px; height: {!! $height !!}px; position: absolute; left: 0%; right: 0%; top: 0%; bottom: 0%; margin:auto;">';
        content += '<div id="yomedia-banner-content" style="width: {!! $width !!}px; height: {!! $height !!}pxpx; position: relative;">';
        content += '<div id="yomedia-banner-bg"></div>';
        content += '<div id="yomedia-bg-{!! $data['zid'] !!}">';
        content += '<a onclick="clickTrackingYomedia_{!! $data['zid'] !!}();">';
        content += '<img id="yomedia-inpage-banner-h" style="margin: 0px auto;display: block; width: {!! $width !!}px; height: {!! $height !!}px;" src="http://demo.yomedia.vn/2015/12/maggi-wap-firstview-ve-tranh/data/416x320.gif">';
        content += '</a>';
        document.body.style.overflow = 'hidden';
        wrapper_{!! $data['zid'] !!}.innerHTML = content;
        avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!! $ovr !!}");
        <?php $data['effect'] = 'Firstview';?>
        @include('ga_campaign')
        @if(!empty($thirdImpressionTrackArr))
            @foreach( $thirdImpressionTrackArr as $item )
                avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
            @endforeach
        @endif
        
        if (typeof _YoImp != 'undefined' && avlHelperModule.validateUrl(_YoImp)) {
            avlHelperModule.embedTracking(_YoImp);
        }
    }

    function closeYoMediaPopupAd_{!! $data['zid'] !!}() {
        document.getElementById('{!! $wrapperAds !!}').remove();
        document.body.style.overflow = 'auto';
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

    showYoMediaPopupAd_{!! $data['zid'] !!}(1);
@include("footer")

