<?php
    $fb_like = isset($data['ad']->fb_like) ? $data['ad']->fb_like : '';
    $fb_margin_right = isset($data['ad']->fb_margin_right) ? $data['ad']->fb_margin_right : 0;
    $fb_margin_bottom = isset($data['ad']->fb_margin_bottom) ? $data['ad']->fb_margin_bottom : 0;
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
    
    $displayType = isset($data['ad']->display_type) ? $data['ad']->display_type : '';
    $htmlSource = preg_replace('/\s\s+/', '', $data['ad']->html_source);
    $htmlSource = str_replace('[yomedia_zone_id]', $data['zid'], $htmlSource);
    $videojs_source = STATIC_URL. 'public/source/interstitial/videojs/';
    if (!empty($data['ad']->source_url_backup)) {
        $videojs_source = $data['ad']->source_url_backup;
    }
?>
avlHelperModule.loadAvlStyle();
avlHelperModule.embedCss('{!! $videojs_source !!}styles/black-poster.css');
avlHelperModule.embedCss('{!! $videojs_source !!}styles/videojs.vast.css');
avlHelperModule.embedCss('{!! $videojs_source !!}styles/video-js.css');
avlHelperModule.embedCss('{!! $videojs_source !!}styles/videojs.vpaid.css');

avlHelperModule.embedJs('{!! $videojs_source !!}js/video.js');
avlHelperModule.embedJs('{!! $videojs_source !!}js/es5-shim.js');
avlHelperModule.embedJs('{!! $videojs_source !!}js/ie8fix.js');
avlHelperModule.embedJs('{!! $videojs_source !!}js/swfobject.js');
avlHelperModule.embedJs('{!! $videojs_source !!}js/videojs-vast-vpaid.min.js');

avlInteractModule.innerHTMLAds('{!! $data['zid'] !!}', '{!! addslashes($htmlSource) !!}', '{!! $displayType !!}');


function showPopupAdYomedia_{!! $data['zid'] !!}(s) {
    var yo_ad = document.getElementById('yomedia-popup-{!! $data['zid'] !!}');
    if (!yo_ad) {
        var banner_html = '<div id="yomedia-overlay-{!! $data['zid'] !!}" style="height: ' + screen.height + 'px; display: block; background-color: black; position: absolute; left: 0; top: 0; width: ' + screen.width + 'px; min-height: 100%; z-index: 1000000; overflow: hidden; opacity: 0.8;"></div>';
        banner_html += '<div id="yomeida-popup-int-video-{!! $data['zid'] !!}" style="width: ' + _yomediaAds_{!! $data['zid'] !!}.popup.width + '; height: ' + _yomediaAds_{!! $data['zid'] !!}.popup.height + '; display: block; position: absolute; top: ' + _yomediaAds_{!! $data['zid'] !!}.popup.top + '; left: 0; right: 0; top: 0; bottom: 0; margin: auto; background-color: #FFFFFF; z-index: 1000001;">';
        if (typeof _yomediaAds_{!! $data['zid'] !!}.close_btn == 'undefined' ||  !_yomediaAds_{!! $data['zid'] !!}.close_btn) {
            banner_html += '<div onclick="closePopupAdYomedia_{!! $data['zid'] !!}(false);" id="yomedia-close-int-v" style="position: absolute; right: 3px;  top: 3px; color: #c3c3c3; font: 12px/12px Arial, sans-serif; cursor: pointer; z-index: 9999; padding: 3px 10px; border-radius: 10px; border: 1px solid #333333; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); background: linear-gradient(to bottom, #666666 0%,#000000 100%);">close</div>';
        } else {
            banner_html += '<div onclick="closePopupAdYomedia_{!! $data['zid'] !!}(false);" id="yomedia-close-int-v" style="position: absolute; right: 3px;  top: 3px;"><img src="' + _yomediaAds_{!! $data['zid'] !!}.close_btn + '"></div>';
        }
        banner_html += '<div id="yomeida-popup-int-con-{!! $data['zid'] !!}">';  

        //video
        if (typeof _yomediaAds_{!! $data['zid'] !!}.video != 'undefined' &&  _yomediaAds_{!! $data['zid'] !!}.video) {
            var video_width = parseInt(_yomediaAds_{!! $data['zid'] !!}.video.width.replace('px', ''));
            var video_height = parseInt(_yomediaAds_{!! $data['zid'] !!}.video.height.replace('px', ''));
            banner_html += '<video id="yomedia-video-{!! $data['zid'] !!}" class="video-js vjs-default-skin" style="position: absolute;" width="' + video_width + '" height="' + video_height + '"><source src="{!! $videojs_source !!}blank.mp4" type="video/mp4"></video>';
        }

        banner_html += '<a onclick="clickTrackingYomedia_{!! $data['zid'] !!}();" target="_blank"><img src="' + _yomediaAds_{!! $data['zid'] !!}.popup.background + '" height="' + _yomediaAds_{!! $data['zid'] !!}.popup.height + '" width="' + _yomediaAds_{!! $data['zid'] !!}.popup.width + '"></a>';
        banner_html += '</div>';//popup_int_con
        banner_html += '</div>';//popup_int_video

        yo_ad = domManipulate.create('div', 'yomedia-popup-{!! $data['zid'] !!}', 'position:fixed; top:0;left:0;right:0;bottom:0; z-index: 9990', banner_html);
        domManipulate.append(yo_ad);
        setTopYomedia_{!! $data['zid'] !!}();

        if (typeof _yomediaAds_{!! $data['zid'] !!}.video != 'undefined' &&  _yomediaAds_{!! $data['zid'] !!}.video) {
            var videoObj_{!! $data['zid'] !!} = videojs("yomedia-video-{!! $data['zid'] !!}", {hls: {withCredentials: true},"controls": true,"autoplay": false, bigPlayButton: false, controlBar: {playToggle: false, fullscreenToggle: false, currentTimeDisplay: false, timeDivider: false, durationDisplay: false, remainingTimeDisplay: false, progressControl: false, volumeControl: false, muteToggle: true}});

            var lVideo = document.getElementById("yomedia-video-{!! $data['zid'] !!}")
            lVideo.style.left = _yomediaAds_{!! $data['zid'] !!}.video.left;
            lVideo.style.top = _yomediaAds_{!! $data['zid'] !!}.video.top;
                    
            videoObj_{!! $data['zid'] !!}.vastClient({
                url: '{!! AD_SERVER_FILE !!}make-vast?ec=0&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&ovr={!! $data['ovr'] !!}&cs={!! $data['checksum'] !!}',
                playAdAlways: true,
                adCancelTimeout: 10000 
            });
            if (typeof _yomediaAds_{!! $data['zid'] !!}.muted === 'boolean') {
                videoObj_{!! $data['zid'] !!}.muted(_yomediaAds_{!! $data['zid'] !!}.muted);
            }
            if (typeof _yomediaAds_{!! $data['zid'] !!}.volume === 'number') {
                videoObj_{!! $data['zid'] !!}.volume(_yomediaAds_{!! $data['zid'] !!}.volume);
            }

            videoObj_{!! $data['zid'] !!}.play();
            videoObj_{!! $data['zid'] !!}.on('ended', function(evt) {
                closePopupAdYomedia_{!! $data['zid'] !!}(true);
            });
        }

        avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!! $ovr !!}");
        <?php $data['effect'] = 'Interstitial_Banner';?>
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
}

function setTopYomedia_{!! $data['zid'] !!}() {
    var scroll = document.body.scrollTop;
    var top = parseInt(_yomediaAds_{!! $data['zid'] !!}.popup.top.replace('px', ''));
    //document.getElementById("yomeida-popup-int-video-{!! $data['zid'] !!}").style.top = (scroll + top) + 'px';
}

function closePopupAdYomedia_{!! $data['zid'] !!}(ended_video) {
    domManipulate.remove('yomedia-popup-{!! $data['zid'] !!}');
    if (!ended_video) {
        clickTrackingYomedia_{!! $data['zid'] !!}();
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
        @if(!empty($htmlSource))
            if (typeof _yomediaAds_{!! $data['zid'] !!} != 'undefined') {
                showPopupAdYomedia_{!! $data['zid'] !!}(1);
            }
        @endif
    }
}
@include("footer")
