<?php
    $destination_url = $data['ad']->destination_url;
    $eidtype         = substr($data['element_id'],0,1);
    $eid             = substr($data['element_id'],1);
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
avlInteractModule.innerHTMLAds('{!! $data['zid'] !!}', '{!! addslashes($htmlSource) !!}', '{!! $displayType !!}');

var imgW = 0, imgH = 0;
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

    a_{!! $data['zid'] !!}.style.cssText = "display: block; opacity: 0.45; overflow: hidden; position: relative; z-index: 99999; width: 100%; background: transparent; visibility: hidden;";
    var previousElement = a_{!! $data['zid'] !!}.previousElementSibling;
    var nextElement = a_{!! $data['zid'] !!}.nextElementSibling;

    if (previousElement) {
        previousElement.style.position = 'relative';
        previousElement.style.zIndex = 990;
    }

    if (nextElement) {
        nextElement.style.position = 'relative';
        nextElement.style.zIndex = 990;
    }

    var swdWidth = screen.width;
    var swdHeight = screen.height;

    var rs = '';
    if (avlInteractModule.isMobile() == true) {
        rs = '<div id="YomediaInpageContent_{!! $data['zid'] !!}" style="display: block; opacity: 0; background: transparent; overflow: hidden; margin: 0px auto; position: fixed; z-index: 1; bottom: 0px; max-width: 100%; height: 332px;">';
        rs += '<a onclick="clickTrackingYomedia_{!! $data['zid'] !!}();">';
        rs += '<img id="yomedia-inpage-banner-h-{!! $data['zid'] !!}" style="margin: 0px auto;display: block;width:100%;max-width: 100%" src="' + _yomediaAds_{!! $data['zid'] !!}.background_h + '">';
        rs += '</a>';
        rs += '<a onclick="clickTrackingYomedia_{!! $data['zid'] !!}();">';
        rs += '<img id="yomedia-inpage-banner-w-{!! $data['zid'] !!}" style="margin: 0px auto;display: none;width: 100%;" src="' + _yomediaAds_{!! $data['zid'] !!}.background_w + '">';
        rs += '</a>';
        rs += '<input type="hidden" value="0" name="hid_height" id="hid_height" />';
        rs += '<input type="hidden" value="0" name="hid_width" id="hid_width" />';
        rs += '</div>';
        domManipulate.getElid('YomediaInpage_{!! $data['zid'] !!}').innerHTML = rs;

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
            a_{!! $data['zid'] !!}.style.height = '1px';
            document.getElementById("YomediaInpageContent_{!! $data['zid'] !!}").style.background = "transparent";

            document.addEventListener("touchmove", showBannerYomedia_{!! $data['zid'] !!}, false);
            document.addEventListener("scroll", showBannerYomedia_{!! $data['zid'] !!}, false);
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
}

function showBannerYomedia_{!! $data['zid'] !!}() {
    var orCh = function() {
        var adContainer_{!! $data['zid'] !!} = document.getElementById("YomediaInpage_{!! $data['zid'] !!}");
        var inpageContent_{!! $data['zid'] !!} = document.getElementById("YomediaInpageContent_{!! $data['zid'] !!}");
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
        if (img_h < swdWidth) {
            document.getElementById('yomedia-inpage-banner-h-{!! $data['zid'] !!}').style.display = 'none';
            document.getElementById('yomedia-inpage-banner-w-{!! $data['zid'] !!}').style.display = 'block';
            if (document.getElementById('yomedia-border-h-{!! $data['zid'] !!}')) {
                document.getElementById('yomedia-border-h-{!! $data['zid'] !!}').style.visibility = 'hidden';
            }
            if (document.getElementById('yomedia-border-w-{!! $data['zid'] !!}')) {
                document.getElementById('yomedia-border-w-{!! $data['zid'] !!}').style.visibility = 'visible';
            }
        } else {
            document.getElementById('yomedia-inpage-banner-w-{!! $data['zid'] !!}').style.display = 'none';
            document.getElementById('yomedia-inpage-banner-h-{!! $data['zid'] !!}').style.display = 'block';

            if (document.getElementById('yomedia-border-w-{!! $data['zid'] !!}')) {
                document.getElementById('yomedia-border-w-{!! $data['zid'] !!}').style.visibility = 'hidden';
            }
            if (document.getElementById('yomedia-border-h-{!! $data['zid'] !!}')) {
                document.getElementById('yomedia-border-h-{!! $data['zid'] !!}').style.visibility = 'visible';
            }
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
        
        if (adContainer_{!! $data['zid'] !!} != null) {
            if (seenByViewportYomedia_{!! $data['zid'] !!}(adContainer_{!! $data['zid'] !!})) {
                adContainer_{!! $data['zid'] !!}.style.opacity = '1';
                adContainer_{!! $data['zid'] !!}.style.background = 'transparent';
                adContainer_{!! $data['zid'] !!}.style.visibility = 'visible';
                inpageContent_{!! $data['zid'] !!}.style.opacity = '1';
            } else {
                adContainer_{!! $data['zid'] !!}.style.visibility = 'hidden';
                inpageContent_{!! $data['zid'] !!}.style.opacity = '0';
            }
        }

    }

    if (navigator.userAgent.match(/Windows Phone/i)) {
        window.onresize = orCh();
    }

    var supportsOrientationChange = "onorientationchange" in window,
        orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";
    document.addEventListener('orientationchange', orCh());
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
