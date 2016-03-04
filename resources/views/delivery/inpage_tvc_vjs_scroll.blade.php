<?php
    $destination_url = $data['ad']->destination_url;
    $eidtype         = substr($data['element_id'],0,1);
    $eid             = substr($data['element_id'],1);
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
?>
avlHelperModule.loadAvlStyle();
avlHelperModule.embedCss('{!! STATIC_URL !!}public/source/wap_inpage/videojs/styles/black-poster.css');
avlHelperModule.embedCss('{!! STATIC_URL !!}public/source/wap_inpage/videojs/styles/videojs.vast.css');
avlHelperModule.embedCss('{!! STATIC_URL !!}public/source/wap_inpage/videojs/styles/video-js.css');
avlHelperModule.embedCss('{!! STATIC_URL !!}public/source/wap_inpage/videojs/styles/videojs.vpaid.css');

avlHelperModule.embedJs('{!! STATIC_URL !!}public/source/wap_inpage/videojs/js/video.js');
avlHelperModule.embedJs('{!! STATIC_URL !!}public/source/wap_inpage/videojs/js/es5-shim.js');
avlHelperModule.embedJs('{!! STATIC_URL !!}public/source/wap_inpage/videojs/js/ie8fix.js');
avlHelperModule.embedJs('{!! STATIC_URL !!}public/source/wap_inpage/videojs/js/swfobject.js');
avlHelperModule.embedJs('{!! STATIC_URL !!}public/source/wap_inpage/videojs/js/videojs-vast-vpaid.min.js');

avlInteractModule.innerHTMLAds('{!! $data['zid'] !!}', '{!! addslashes($htmlSource) !!}', '{!! $displayType !!}');

var imgW = 0, imgH = 0, fnext = 0;
var play_button_{!! $data['zid'] !!} = "yomedia-play-h-{!! $data['zid'] !!}";
var start_{!! $data['zid'] !!} = 0;
var iOS_{!! $data['zid'] !!} = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
var videoObj_{!! $data['zid'] !!} = null, yomedia_video_status_{!! $data['zid'] !!} = null;
function showPopupAdYomedia_{!! $data['zid'] !!}(s) {
    var a_{!! $data['zid'] !!} = document.getElementById('YomediaInpage_{!! $data['zid'] !!}');

    if (a_{!! $data['zid'] !!} == null) {
        @if ($eid)
            @if($eidtype == '#')
                var content_{!! $data['zid'] !!} = document.getElementById('{!! $eid !!}');
            @else
                var content_{!! $data['zid'] !!} = document.getElementsByClassName('{!! $eid !!}')[0];
            @endif
            if (typeof content_{!! $data['zid'] !!} != 'undefined' && content_{!! $data['zid'] !!} != null) {
                e_{!! $data['zid'] !!} = content_{!! $data['zid'] !!}.childNodes;
                var p_{!! $data['zid'] !!} = 0;
                for (var i_{!! $data['zid'] !!} = 0; i_{!! $data['zid'] !!} < e_{!! $data['zid'] !!}.length; i_{!! $data['zid'] !!}++) {
                    if (e_{!! $data['zid'] !!}[i_{!! $data['zid'] !!}].clientHeight > 0) {
                        p_{!! $data['zid'] !!} = p_{!! $data['zid'] !!} + e_{!! $data['zid'] !!}[i_{!! $data['zid'] !!}].clientHeight;
                    }
    
                    if (p_{!! $data['zid'] !!} >= (content_{!! $data['zid'] !!}.clientHeight / 2)) {
                        if (typeof(content_{!! $data['zid'] !!}.childNodes[i_{!! $data['zid'] !!} + 1]) != 'undefined') {
                            var eleToInsert = i_{!! $data['zid'] !!} + 1;
                        } else {
                            var eleToInsert = i_{!! $data['zid'] !!};
                        }
                        
                        a_{!! $data['zid'] !!} = domManipulate.create('div', 'YomediaInpage_{!! $data['zid'] !!}', '', ''), content_{!! $data['zid'] !!}.insertBefore(a_{!! $data['zid'] !!}, content_{!! $data['zid'] !!}.childNodes[eleToInsert]);
                        break;
                    }
                }
            } else {
                a_{!! $data['zid'] !!} = domManipulate.create('div', 'YomediaInpage_{!! $data['zid'] !!}');
                domManipulate.append(a_{!! $data['zid'] !!});
            }
        @else {
            a_{!! $data['zid'] !!} = domManipulate.create('div', 'YomediaInpage_{!! $data['zid'] !!}');
            domManipulate.append(a_{!! $data['zid'] !!});
        }
        @endif
        
    }

    if (a_{!! $data['zid'] !!} == null) {
        return false;
    }

    a_{!! $data['zid'] !!}.style.cssText = "display: block; opacity: 0.45; overflow: hidden; position: relative; z-index: 996; width: 100%; background: transparent; visibility: hidden;";
    var previousElement = a_{!! $data['zid'] !!}.previousElementSibling;
    var nextElement = a_{!! $data['zid'] !!}.nextElementSibling;

    if (previousElement) {
        previousElement.style.position = 'relative';
        previousElement.style.zIndex = 998;
    }

    if (nextElement) {
        nextElement.style.position = 'relative';
        nextElement.style.zIndex = 998;
    }

    var swdWidth = screen.width;
    var swdHeight = screen.height;

    var rs = '';
    if (avlInteractModule.isMobile() == true) {
        if (screen.width < screen.height) {
            var top = parseInt(_yomediaAds_{!! $data['zid'] !!}.video_top_h.replace('%', ''));
            top = calculateSizeYomedia_{!! $data['zid'] !!}(screen.width, screen.height, top, 'portrait') + '%';
        } else {
            var top = parseInt(_yomediaAds_{!! $data['zid'] !!}.video_top_w.replace('%', ''));
            top = calculateSizeYomedia_{!! $data['zid'] !!}(screen.width, screen.height, top, 'landscape') + '%';
        }
        rs = '<div id="YomediaInpageContent_{!! $data['zid'] !!}" style="display: block; opacity: 0; background: transparent; overflow: hidden; margin: 0px auto; position: fixed; z-index: 1; bottom: 0px; max-width: 100%; height: 332px;">';
        rs += '<a onclick="clickTrackingYomedia_{!! $data['zid'] !!}();">';
        rs += '<img id="yomedia-inpage-banner-h-{!! $data['zid'] !!}" style="margin: 0px auto;display: block;width:100%;max-width: 100%" src="' + _yomediaAds_{!! $data['zid'] !!}.background_h + '">';
        rs += '</a>';
        rs += '<a onclick="clickTrackingYomedia_{!! $data['zid'] !!}();">';
        rs += '<img id="yomedia-inpage-banner-w-{!! $data['zid'] !!}" style="margin: 0px auto;display: none;width: 100%;" src="' + _yomediaAds_{!! $data['zid'] !!}.background_w + '">';
        rs += '</a>';
        rs += '<input type="hidden" value="0" name="hid_height" id="hid_height" />';
        rs += '<input type="hidden" value="0" name="hid_width" id="hid_width" />';
        // Inner
        rs += '<div id="innerVideo-{!! $data['zid'] !!}" style="top:' + top + ';left:' + _yomediaAds_{!! $data['zid'] !!}.video_left_h + ';right:' + _yomediaAds_{!! $data['zid'] !!}.video_right_h + ';margin-left:auto;margin-right:auto;position:absolute;width:' + _yomediaAds_{!! $data['zid'] !!}.video_width_h + ';height:' + _yomediaAds_{!! $data['zid'] !!}.video_height_h + ';z-index:1">';
        rs += '<img onclick="playVideoYomedia_{!! $data['zid'] !!}(\'yomedia-play-h-{!! $data['zid'] !!}\');" id="yomedia-play-h-{!! $data['zid'] !!}" style="position:absolute; z-index:1; max-width: 100%;" src="' + _yomediaAds_{!! $data['zid'] !!}.video_poster_h + '" />';
        rs += '<img onclick="playVideoYomedia_{!! $data['zid'] !!}(\'yomedia-play-w-{!! $data['zid'] !!}\');" id="yomedia-play-w-{!! $data['zid'] !!}" style="position:absolute;visibility: hidden;z-index:1; max-width: 100%;" src="' + _yomediaAds_{!! $data['zid'] !!}.video_poster_w + '" />';
        
        if (typeof _yomediaAds_{!! $data['zid'] !!}.border_h != 'undefined' && _yomediaAds_{!! $data['zid'] !!}.border_h != '') {
            rs += '<img id="yomedia-border-h-{!! $data['zid'] !!}" src="'+_yomediaAds_{!! $data['zid'] !!}.border_h+'" style="position: absolute; width: 100%; z-index: 0; max-width: 100%; visibility: hidden;">';
        }
        if (typeof _yomediaAds_{!! $data['zid'] !!}.border_w != 'undefined' && _yomediaAds_{!! $data['zid'] !!}.border_w != '') {
            rs += '<img id="yomedia-border-w-{!! $data['zid'] !!}" src="'+_yomediaAds_{!! $data['zid'] !!}.border_w+'" style="position: absolute; width: 100%; z-index: 0; max-width: 100%; visibility: hidden;">';
        }

        rs += '<video id="yomedia-video-{!! $data['zid'] !!}" class="video-js vjs-default-skin" style="position: static; visibility: hidden; min-height: 0px;width: 100%;"><source src="{!! STATIC_URL !!}public/source/wap_inpage/videojs/blank.mp4" type="video/mp4"></video>';

        rs += '</div>'; //End inner        
        rs += '</div>';
        domManipulate.getElid('YomediaInpage_{!! $data['zid'] !!}').innerHTML = rs;

        var innerVideo_{!! $data['zid'] !!} = document.getElementById("innerVideo-{!! $data['zid'] !!}");
        var lVideo = document.getElementById("yomedia-video-{!! $data['zid'] !!}")
        lVideo.width = innerVideo_{!! $data['zid'] !!}.clientWidth;
        //lVideo.height = innerVideo_{!! $data['zid'] !!}.clientHeight;

        videoObj_{!! $data['zid'] !!} = videojs("yomedia-video-{!! $data['zid'] !!}", {hls: {withCredentials: true},"controls": true,"autoplay": false, bigPlayButton: false, controlBar: {playToggle: false, fullscreenToggle: false, currentTimeDisplay: false, timeDivider: false, durationDisplay: false, remainingTimeDisplay: false, progressControl: false, volumeControl: false, muteToggle: true}});
                
        videoObj_{!! $data['zid'] !!}.vastClient({
            url: '{!! AD_SERVER_FILE !!}make-vast?ec=0&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&ovr={!! $data['ovr'] !!}&cs={!! $data['checksum'] !!}',
            playAdAlways: true,
            adCancelTimeout: 10000 
        });
        videoObj_{!! $data['zid'] !!}.muted(_yomediaAds_{!! $data['zid'] !!}.muted);

        var height_body = document.body.offsetHeight;

        var hid_height = document.getElementById('hid_height');
        var hid_width = document.getElementById('hid_width');
        hid_height.value = getWindowWidthYomedia_{!! $data['zid'] !!}();
        hid_width.value = getWindowHeightYomedia_{!! $data['zid'] !!}();

        var image = new Image();
        image.src = document.getElementById("yomedia-inpage-banner-h-{!! $data['zid'] !!}").src;
        image.onload = function() {
            imgW = this.width;
            imgH = this.height;
            a_{!! $data['zid'] !!}.style.height = screen.height;
            document.getElementById("YomediaInpageContent_{!! $data['zid'] !!}").style.background = "transparent";

            if (window!=window.top) {
                parent.document.addEventListener("touchmove", showBannerYomedia_{!! $data['zid'] !!}, false);
                parent.document.addEventListener("scroll", showBannerYomedia_{!! $data['zid'] !!}, false);
            }else{
                document.addEventListener("touchmove", showBannerYomedia_{!! $data['zid'] !!}, false);
                document.addEventListener("scroll", showBannerYomedia_{!! $data['zid'] !!}, false);
            }  
        }

        avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!! $ovr !!}");
        <?php $data['effect'] = 'Inpage';?>
        @include('ga_campaign')
        @if(!empty($thirdImpressionTrackArr))
            @foreach( $thirdImpressionTrackArr as $item )
                avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
            @endforeach
        @endif
        
        if (typeof _YoImp != 'undefined' && avlHelperModule.validateUrl(_YoImp)) {
            avlHelperModule.embedTracking(_YoImp);
        }

    } else {
        domManipulate.getElid('YomediaInpage_{!! $data['zid'] !!}').style.display = 'none';
    }
    if (iOS_{!! $data['zid'] !!}){
        var inpageYo = document.getElementById('YomediaInpage_{!! $data['zid'] !!}');
        inpageYo.addEventListener("touchstart", videoStart_{!! $data['zid'] !!}, false);
    }
}

function videoStart_{!! $data['zid'] !!}(){
    if (yomedia_video_status_{!! $data['zid'] !!} == null && _yomediaAds_{!! $data['zid'] !!}.auto_play) {
        playVideoYomedia_{!! $data['zid'] !!}(play_button_{!! $data['zid'] !!});
    }
}

function showBannerYomedia_{!! $data['zid'] !!}() {
    var orCh = function() {
        var adContainer_{!! $data['zid'] !!} = document.getElementById("YomediaInpage_{!! $data['zid'] !!}");
        var inpageContent_{!! $data['zid'] !!} = document.getElementById("YomediaInpageContent_{!! $data['zid'] !!}");
        var innerVideo_{!! $data['zid'] !!} = document.getElementById("innerVideo-{!! $data['zid'] !!}");
        var check_w = hid_width.value;
        var swdWidth = getWindowWidthYomedia_{!! $data['zid'] !!}();
        var swdHeight = getWindowHeightYomedia_{!! $data['zid'] !!}();
        if (swdWidth == 0) {
            swdWidth = screen.width;
        }

        if (swdHeight == 0) {
            swdHeight = screen.height;
        }

        if (check_w < swdWidth) {
            hid_height.value = swdHeight;
            document.getElementById("hid_width").value = swdWidth;
        } else {
            document.getElementById("hid_width").value = swdWidth;
        }

        var wPar = adContainer_{!! $data['zid'] !!}.clientWidth ? adContainer_{!! $data['zid'] !!}.clientWidth : adContainer_{!! $data['zid'] !!}.offsetWidth;

        if (window.innerHeight) {
            var img_h = window.innerHeight;
        } else {
            var img_h = swdHeight; // the height default by 100% height screen
        }
        var check_h = document.getElementById("hid_height").value;
        if (img_h > check_h) {
            hid_height.value = img_h;
        } else {
            img_h = check_h;
        }
        img_h = parseInt(img_h);

        var lVideo = document.getElementById("yomedia-video-{!! $data['zid'] !!}");
        var lVideo_html5_api = document.getElementById("yomedia-video-{!! $data['zid'] !!}_html5_api");
        if (img_h < swdWidth) {
            document.getElementById('yomedia-inpage-banner-h-{!! $data['zid'] !!}').style.display = 'none';
            document.getElementById('yomedia-inpage-banner-w-{!! $data['zid'] !!}').style.display = 'block';

            var top = parseInt(_yomediaAds_{!! $data['zid'] !!}.video_top_w.replace('%', ''));
            top = calculateSizeYomedia_{!! $data['zid'] !!}(adContainer_{!! $data['zid'] !!}.offsetWidth, adContainer_{!! $data['zid'] !!}.offsetHeight, top, 'landscape') + '%';
            
            innerVideo_{!! $data['zid'] !!}.style.width = _yomediaAds_{!! $data['zid'] !!}.video_width_w;
            innerVideo_{!! $data['zid'] !!}.style.height = _yomediaAds_{!! $data['zid'] !!}.video_height_w;
            innerVideo_{!! $data['zid'] !!}.style.top = top;
            innerVideo_{!! $data['zid'] !!}.style.left = _yomediaAds_{!! $data['zid'] !!}.video_left_w;
            innerVideo_{!! $data['zid'] !!}.style.right = _yomediaAds_{!! $data['zid'] !!}.video_right_w;

            lVideo.style.cssText = "position: static; min-height:0px;z-index:1;margin-left:"+_yomediaAds_{!! $data['zid'] !!}.border_left_w+"; margin-top: "+_yomediaAds_{!! $data['zid'] !!}.border_top_w+";";
            lVideo.getElementsByClassName('vjs-mute-control')[0].style.cssFloat = 'left';
            lVideo.getElementsByClassName('vjs-mute-control')[0].style.marginLeft = _yomediaAds_{!! $data['zid'] !!}.border_left_w;

            lVideo.style.width = innerVideo_{!! $data['zid'] !!}.clientWidth * (parseFloat(_yomediaAds_{!! $data['zid'] !!}.border_width_w)/100);
            var h = innerVideo_{!! $data['zid'] !!}.clientHeight * (parseFloat(_yomediaAds_{!! $data['zid'] !!}.border_height_w)/100);
            h = calculateSizeYomedia_{!! $data['zid'] !!}(adContainer_{!! $data['zid'] !!}.offsetWidth, adContainer_{!! $data['zid'] !!}.offsetHeight, h, 'landscape');
            lVideo.style.height = h;

            if (yomedia_video_status_{!! $data['zid'] !!} == null || yomedia_video_status_{!! $data['zid'] !!} == 'ended') {
                document.getElementById('yomedia-play-h-{!! $data['zid'] !!}').style.visibility = 'hidden';
                document.getElementById('yomedia-play-w-{!! $data['zid'] !!}').style.visibility = 'visible';
            }
            if (document.getElementById('yomedia-border-h-{!! $data['zid'] !!}')) {
                document.getElementById('yomedia-border-h-{!! $data['zid'] !!}').style.visibility = 'hidden';
            }
            if (document.getElementById('yomedia-border-w-{!! $data['zid'] !!}')) {
                document.getElementById('yomedia-border-w-{!! $data['zid'] !!}').style.visibility = 'visible';
            }
            play_button_{!! $data['zid'] !!} = "yomedia-play-w-{!! $data['zid'] !!}";
        } else {
            document.getElementById('yomedia-inpage-banner-w-{!! $data['zid'] !!}').style.display = 'none';
            document.getElementById('yomedia-inpage-banner-h-{!! $data['zid'] !!}').style.display = 'block';

            var top = parseInt(_yomediaAds_{!! $data['zid'] !!}.video_top_h.replace('%', ''));
            top = calculateSizeYomedia_{!! $data['zid'] !!}(adContainer_{!! $data['zid'] !!}.offsetWidth, adContainer_{!! $data['zid'] !!}.offsetHeight, top, 'portrait') + '%';
            
            innerVideo_{!! $data['zid'] !!}.style.width = _yomediaAds_{!! $data['zid'] !!}.video_width_h;
            innerVideo_{!! $data['zid'] !!}.style.height = _yomediaAds_{!! $data['zid'] !!}.video_height_h;
            innerVideo_{!! $data['zid'] !!}.style.top = top;
            innerVideo_{!! $data['zid'] !!}.style.left = _yomediaAds_{!! $data['zid'] !!}.video_left_h;
            innerVideo_{!! $data['zid'] !!}.style.right = _yomediaAds_{!! $data['zid'] !!}.video_right_h;

            lVideo.style.cssText = "position: static; min-height:0px;z-index:1;margin-left:"+_yomediaAds_{!! $data['zid'] !!}.border_left_h+"; margin-top: "+_yomediaAds_{!! $data['zid'] !!}.border_top_h+";";
            lVideo.getElementsByClassName('vjs-mute-control')[0].style.cssFloat = 'left';
            lVideo.getElementsByClassName('vjs-mute-control')[0].style.marginLeft = _yomediaAds_{!! $data['zid'] !!}.border_left_h;
    
            lVideo.style.width = innerVideo_{!! $data['zid'] !!}.clientWidth * (parseFloat(_yomediaAds_{!! $data['zid'] !!}.border_width_h)/100);
            var h = innerVideo_{!! $data['zid'] !!}.clientHeight * (parseFloat(_yomediaAds_{!! $data['zid'] !!}.border_height_h)/100);
            h = calculateSizeYomedia_{!! $data['zid'] !!}(adContainer_{!! $data['zid'] !!}.offsetWidth, adContainer_{!! $data['zid'] !!}.offsetHeight, h, 'portrait');
            lVideo.style.height = h;

            if (yomedia_video_status_{!! $data['zid'] !!} == null || yomedia_video_status_{!! $data['zid'] !!} == 'ended') {
                document.getElementById('yomedia-play-w-{!! $data['zid'] !!}').style.visibility = 'hidden';
                document.getElementById('yomedia-play-h-{!! $data['zid'] !!}').style.visibility = 'visible';
            }
            if (document.getElementById('yomedia-border-w-{!! $data['zid'] !!}')) {
                document.getElementById('yomedia-border-w-{!! $data['zid'] !!}').style.visibility = 'hidden';
            }
            if (document.getElementById('yomedia-border-h-{!! $data['zid'] !!}')) {
                document.getElementById('yomedia-border-h-{!! $data['zid'] !!}').style.visibility = 'visible';
            }
            play_button_{!! $data['zid'] !!} = "yomedia-play-h-{!! $data['zid'] !!}";
        }
        if (img_h <= imgH) {
            adContainer_{!! $data['zid'] !!}.style.height = (img_h) + 'px';
            inpageContent_{!! $data['zid'] !!}.style.height = img_h + 'px';

        } else {
            adContainer_{!! $data['zid'] !!}.style.height = (imgH) + 'px';
            inpageContent_{!! $data['zid'] !!}.style.height = imgH + 'px';
            inpageContent_{!! $data['zid'] !!}.style.position = 'static';
        }

        var browser = navigator.appName;
        var w = wPar;
        if (wPar > imgW) {
            w = imgW;
        }

        if (browser.indexOf("Internet Explorer") > -1) {
            inpageContent_{!! $data['zid'] !!}.style.width = w + 'px';
        } else {
            var leftM = 0;
            if (swdWidth > wPar) var leftM = (swdWidth - wPar) / 2
            inpageContent_{!! $data['zid'] !!}.style.width = w + 'px';
            inpageContent_{!! $data['zid'] !!}.style.left = leftM + 'px';
        }
        
        if (yomedia_video_status_{!! $data['zid'] !!} != null) {
            if (!visibleVideoYomedia_{!! $data['zid'] !!}(adContainer_{!! $data['zid'] !!}, innerVideo_{!! $data['zid'] !!})) {
                if (yomedia_video_status_{!! $data['zid'] !!} == 'played') {
                    videoObj_{!! $data['zid'] !!}.pause();
                    yomedia_video_status_{!! $data['zid'] !!} = 'paused';
                }
            } else if (yomedia_video_status_{!! $data['zid'] !!} == 'paused' && _yomediaAds_{!! $data['zid'] !!}.auto_play) {
                videoObj_{!! $data['zid'] !!}.play();
                yomedia_video_status_{!! $data['zid'] !!} = 'played';
            }
        } else {
            if (visibleVideoYomedia_{!! $data['zid'] !!}(adContainer_{!! $data['zid'] !!}, innerVideo_{!! $data['zid'] !!}) && _yomediaAds_{!! $data['zid'] !!}.auto_play) {
                playVideoYomedia_{!! $data['zid'] !!}(play_button_{!! $data['zid'] !!});
            }
        }
        
        if (adContainer_{!! $data['zid'] !!} != null) {
            if (seenByViewportYomedia_{!! $data['zid'] !!}(adContainer_{!! $data['zid'] !!})) {
                adContainer_{!! $data['zid'] !!}.style.opacity = '1';
                adContainer_{!! $data['zid'] !!}.style.background = 'transparent';
                adContainer_{!! $data['zid'] !!}.style.visibility = 'visible';
                inpageContent_{!! $data['zid'] !!}.style.opacity = '1';
                //document.getElementById('more-view-{!! $data['zid'] !!}').style.opacity = '1';
                innerVideo_{!! $data['zid'] !!}.style.visibility = 'visible';
                if (yomedia_video_status_{!! $data['zid'] !!} != null && yomedia_video_status_{!! $data['zid'] !!} != 'ended') {
                    lVideo.style.visibility = 'visible';
                    lVideo_html5_api.style.visibility = 'visible';
                } else {
                    lVideo.style.visibility = 'hidden';
                    lVideo_html5_api.style.visibility = 'hidden';       
                }
            } else {
                adContainer_{!! $data['zid'] !!}.style.visibility = 'hidden';
                inpageContent_{!! $data['zid'] !!}.style.opacity = '0';
                //document.getElementById('more-view-{!! $data['zid'] !!}').style.opacity = '0';
                innerVideo_{!! $data['zid'] !!}.style.visibility = 'hidden';
                lVideo.style.visibility = 'hidden';
                lVideo_html5_api.style.visibility = 'hidden';
                if (document.getElementById('yomedia-border-w-{!! $data['zid'] !!}')) {
                    document.getElementById('yomedia-border-w-{!! $data['zid'] !!}').style.visibility = 'hidden';
                }
                if (document.getElementById('yomedia-border-h-{!! $data['zid'] !!}')) {
                    document.getElementById('yomedia-border-h-{!! $data['zid'] !!}').style.visibility = 'hidden';
                }
            }
        }
    }

    if (navigator.userAgent.match(/Windows Phone/i)) {
        window.onresize = orCh();
    }

    var supportsOrientationChange = "onorientationchange" in window,
        orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";
    if (window!=window.top) {
        parent.document.addEventListener('orientationchange', orCh());
    }else{
        document.addEventListener('orientationchange', orCh());
    }
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

window.scroller = function() {
    var x = 0;
    var y = 0;
    if (typeof window.pageYOffset == "number") {
        y = window.pageYOffset;
        x = window.pageXOffset;
    } else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
        y = document.body.scrollTop;
        x = document.body.scrollLeft;
    } else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
        y = document.documentElement.scrollTop;
        x = document.documentElement.scrollLeft;
    }
    return {
        x: x,
        y: y
    };
}

function getOffsetYYomedia_{!! $data['zid'] !!}(obj) {
    var y = 0;
    if (obj.offsetParent) {
        while (obj) {
            y += obj.offsetTop;
            obj = obj.offsetParent;
        }
    } else if (obj.y) {
        y += obj.y;
    }
    return y;
}


function getViewPortHeightYomedia_{!! $data['zid'] !!}() {
    var de = document.documentElement;

    if (!!window.innerWidth) {
        return window.innerHeight;
    } else if (de && !isNaN(de.clientHeight)) {
        return de.clientHeight;
    }

    return 0;
}

function seenByViewportYomedia_{!! $data['zid'] !!}(obj) {
    var vpH = getViewPortHeightYomedia_{!! $data['zid'] !!}(),
        windowY = window.scroller().y, // Scroll Top
        elementY = getOffsetYYomedia_{!! $data['zid'] !!}(obj);
    elementHeight = obj.clientHeight;
    ybottom = elementY + elementHeight;
    endViewPort = vpH + windowY;

    if ((ybottom >= windowY && ybottom <= endViewPort) || (elementY >= windowY && elementY <= endViewPort)) {
        return true;
    } else {
        return false;
    }
}

function visibleVideoYomedia_{!! $data['zid'] !!}(objBg, objVideo) {
    var vpH = getViewPortHeightYomedia_{!! $data['zid'] !!}(),
        windowY = window.scroller().y, // Scroll Top
        elementY = getOffsetYYomedia_{!! $data['zid'] !!}(objBg);
    elementHeight = objBg.clientHeight;
    ybottom = elementY + elementHeight;
    endViewPort = vpH + windowY;

    videoHeight = objVideo.clientHeight;
    videoTop = objVideo.offsetTop;

    if (ybottom >= (windowY + videoHeight + videoTop) && elementY <= (windowY + videoTop)) {
        return true;
    } else {
        return false;
    }
}

function getWindowWidthYomedia_{!! $data['zid'] !!}() {
    var myWidth;
    if (typeof(window.innerWidth) == 'number') {
        //Non-IE
        myWidth = window.innerWidth;

    } else if (document.documentElement && (document.documentElement.clientWidth)) {
        //IE 6+ in 'standards compliant mode'
        myWidth = document.documentElement.clientWidth;

    } else if (document.body && (document.body.clientWidth)) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;

    }
    return myWidth;
}

function getWindowHeightYomedia_{!! $data['zid'] !!}() {
    var myHeight;

    if (typeof(window.innerHeight) == 'number') {
        //Non-IE
        myHeight = window.innerHeight;
    } else if (document.documentElement && (document.documentElement.clientHeight)) {
        //IE 6+ in 'standards compliant mode'
        myHeight = document.documentElement.clientHeight;
    } else if (document.body && (document.body.clientHeight)) {
        //IE 4 compatible
        myHeight = document.body.clientHeight;
    }

    return myHeight;
};

function playVideoYomedia_{!! $data['zid'] !!}(type) {
    if (yomedia_video_status_{!! $data['zid'] !!} == null || yomedia_video_status_{!! $data['zid'] !!} == 'ended') {
        var play = document.getElementById(type);
        var video_{!! $data['zid'] !!} = document.getElementById('yomedia-video-{!! $data['zid'] !!}');
        var video_html5_api_{!! $data['zid'] !!} = document.getElementById('yomedia-video-{!! $data['zid'] !!}_html5_api');
        videoObj_{!! $data['zid'] !!}.play();
        if (!videoObj_{!! $data['zid'] !!}.paused()) {
            play.style.visibility = "hidden";
            video_{!! $data['zid'] !!}.style.visibility = "visible";
            video_html5_api_{!! $data['zid'] !!}.style.visibility = "visible";
            yomedia_video_status_{!! $data['zid'] !!} = 'played';
        }
        videoObj_{!! $data['zid'] !!}.on('ended', function(evt) {
            yomedia_video_status_{!! $data['zid'] !!} = 'ended';
            play.style.visibility = "visible";
            video_{!! $data['zid'] !!}.style.visibility = "hidden";
            video_html5_api_{!! $data['zid'] !!}.style.visibility = "hidden";
        });  
    }
}

function calculateSizeYomedia_{!! $data['zid'] !!}(width, height, obj, type){
    var ratio = screen.width / screen.height;
    if (height != 0) {
        ratio = parseFloat(width) / parseFloat(height);
    }
    
    if (type == 'landscape') {
        obj += obj * (ratio - (480 / 320));
    } else {
        obj += obj * (ratio - (320 / 480));
    }
    
    return obj;
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
