<?php
    $wrapperAds = 'YoMediaBalloon';
    $elAds      = "YoMediaBalloon_".$data['zid'];
    $iWidth     = $data['ad']->width;
    $iHeight    = $data['ad']->height;
    $eWidth     = $data['ad']->width_after;
    $eHeight    = $data['ad']->height_after;

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
var YomediaFlagExpanded_{{$data['zid']}} = false;
var YomediaFlashURI_{{$data['zid']}} = '{{$data['ad']->source_url}}';
var YomediaFlash2URI_{{$data['zid']}} = '{{$data['ad']->source_url2}}';
var eWidth_{{ $data['zid'] }}  = {{$eWidth}};
var eHeight_{{ $data['zid'] }} = {{$eHeight}};
var iWidth_{{ $data['zid'] }}  = {{$iWidth}};
var iHeight_{{ $data['zid'] }} = {{$iHeight}};



function closeYoMediaExpand_{{$data['zid']}}() {
    document.getElementById("YomediaBalloonSidekickFullBanner").innerHTML = "";
    document.getElementById("YomediaBalloonBgMask") ? (document.getElementById("YomediaBalloonBgMask").style.width = "1px", document.getElementById("YomediaBalloonBgMask").style.height = "1px", document.getElementById("YomediaBalloonBgMask").style.backgroundImage = "") : (document.getElementById("YomediaBalloonSidekick").style.width = "1px", document.getElementById("YomediaBalloonSidekick").style.height = "1px");
    document.body.style.overflow = "auto";
    showYoMediaPopupAd_{{$data['zid']}}(1);
}
function showYoMediaPopupAd_{{$data['zid']}}(s) {
    YomediaFlagExpanded_{{$data['zid']}} = false;
    var sPos = 'right-bottom';
    Default ='';
    
    var impressionTrack = encodeURIComponent("{{ App\Models\Tracking::impressionTrackingLink($data['aid'], $data['fpid'], $data['zid'], $data['checksum']) }}{{ !empty($data['ovr']) ? "&ovr=1" : '' }}");
<?php if(!empty($thirdImpressionTrackArr)){ 
    foreach( $thirdImpressionTrackArr as $item ){ ?>
        impressionTrack += '|'+encodeURIComponent("{{trim(str_replace('[timestamp]', time(), $item))}}");
    <?php } 
    } ?>

    
    var clickTag = encodeURIComponent("{{ App\Models\Tracking::clickTrackingLink($data['ad']->destination_url, $data['aid'], $data['fpid'], $data['zid'], $data['checksum']) }}{{ !empty($data['ovr']) ? "&ovr=1" : '' }}");
    var clickTrack= "";

    <?php 
    if(!empty($thirdClickTrackArr)){ 
        $count = 0; 
        foreach( $thirdClickTrackArr as $item ){ 
            $count++; 
            if( $count == 1 ){ ?>
                clickTrack += encodeURIComponent("{{trim(str_replace('[timestamp]', time(), $item))}}");
            <?php }else{ ?>
                clickTrack += '|'+encodeURIComponent("{{trim(str_replace('[timestamp]', time(), $item))}}");
            <?php 
            } 
        } 
    } ?>
    

    var flashURI = YomediaFlashURI_{{ $data['zid'] }}+'?impression='+impressionTrack+"&clickTrack="+clickTrack+"&clickUrl="+clickTag;

    avlInteractModule.initBalloon(
        '{{$wrapperAds}}','{{$elAds}}',eWidth_{{ $data['zid'] }},eHeight_{{ $data['zid'] }},iWidth_{{ $data['zid'] }},iHeight_{{ $data['zid'] }}, flashURI,Default,'VIB',parseInt('{{$data['zid']}}'),'popup','top-down','{{$data['ad']->flash_wmode}}','VN', parseInt('1'),sPos
    );
    avlInteractModule.showBalloon('{{$wrapperAds}}', sPos, iWidth_{{ $data['zid'] }}, iHeight_{{ $data['zid'] }}, eWidth_{{ $data['zid'] }}, eHeight_{{ $data['zid'] }}, '{{$elAds}}', 'min', parseInt('30000'), parseInt('900000'));   
}


function minYoMediaPopupAd_{{$data['zid']}}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectAd('{{$wrapperAds}}', 'top-down', sPos, 'min', eWidth_{{ $data['zid'] }}, eHeight_{{ $data['zid'] }}, iWidth_{{ $data['zid'] }}, iHeight_{{ $data['zid'] }}, 100);
}


function restoreYoMediaPopupAd_{{$data['zid']}}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectAd('{{$wrapperAds}}', 'top-down', sPos, 'max', eWidth_{{ $data['zid'] }}, eHeight_{{ $data['zid'] }}, iWidth_{{ $data['zid'] }}, iHeight_{{ $data['zid'] }}, 100);
}

function setYoMediaExpand_{{$data['zid']}}() {
    if(YomediaFlagExpanded_{{$data['zid']}}){
        return false;
    }

    var a = document.getElementById("YomediaBalloonSidekickFullBanner"), b = document.getElementById("YomediaBalloonBgMask");
    void 0 == a && (a = domManipulate.create('div','YomediaBalloonSidekickFullBanner','border: 0px none; margin: 0px; padding: 0px; text-align: left; overflow: visible; position: fixed; z-index: 100000; top: 0px; left: 0px;',''), document.body.insertBefore(a, document.body.childNodes[0]));
    void 0 == b && (b = domManipulate.create('div','YomediaBalloonBgMask','border: 0px none; margin: 0px; padding: 0px; text-align: left; overflow: visible; position: fixed; z-index: 9999; top: 0px; left: 0px;',''),
    document.body.insertBefore(b, document.body.childNodes[0]));
    b.setAttribute('onclick', 'closeYoMediaExpand_{{$data['zid']}}()');
    b.style.width = "100%";
    b.style.height = "100%";
    b.style.backgroundImage = 'url("http://static.yomedia.vn/yomedia/common/lightbox_overlay.png")';
    a.innerHTML = '<div id="YomediaBalloonSidekickBanner" style="border: 0 none;position: absolute;"><div id="YomediaBalloonSidekickBannerExpand"></div></div>';
    var e = document.getElementById("YomediaBalloonSidekickBanner");
    var viewportwidth;
    var viewportheight;
    if (typeof window.innerWidth != 'undefined')
    {
        viewportwidth = window.innerWidth,
        viewportheight = window.innerHeight
    }
    else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0)
    {
        viewportwidth = document.documentElement.clientWidth,
        viewportheight = document.documentElement.clientHeight
    }else{
        viewportwidth = document.getElementsByTagName('body')[0].clientWidth,viewportheight = document.getElementsByTagName('body')[0].clientHeight
    }
    var left =  (viewportwidth - eWidth_{{ $data['zid'] }})/2;
    e.style.top    = "47px";
    e.style.left   = left + "px";
    e.style.width  = eWidth_{{ $data['zid'] }}+"px";
    e.style.height = eHeight_{{ $data['zid'] }}+"px";
    document.body.style.overflow = "hidden";

    var bL = {
        zid: {{ $data['zid'] }}
    };
    var bM = {
        allowScriptAccess: "always",
        allowDomain: "*",
        quality: "high",
        wmode: "transparent"
    };
    var bN = {
        id: "YomediaBalloonSidekickBannerExpand",
        name: "YomediaBalloonSidekickBannerExpand"
    };
    YomediaFlagExpanded_{{$data['zid']}} = true;

    var expandTrack = encodeURIComponent("{{ App\Models\Tracking::expandTrackingLink($data['aid'], $data['fpid'], $data['zid'], $data['checksum']) }}{{ !empty($data['ovr']) ? "&ovr=1" : '' }}");
    
    var clickTrack = '';
    <?php 
    if(!empty($thirdClickTrackArr)){ 
        $count = 0; 
        foreach( $thirdClickTrackArr as $item ){ 
            $count++; 
            if( $count == 1 ){ ?>
                clickTrack += encodeURIComponent("{{trim(str_replace('[timestamp]', time(), $item))}}");
            <?php }else{ ?>
                clickTrack += '|'+encodeURIComponent("{{trim(str_replace('[timestamp]', time(), $item))}}");
            <?php } 
        } 
    } ?>
    
    var clickUrl = encodeURIComponent("{{ App\Models\Tracking::clickTrackingLink($data['ad']->destination_url, $data['aid'], $data['fpid'], $data['zid'], $data['checksum']) }}{{ !empty($data['ovr']) ? "&ovr=1" : '' }}");
    var flashURI = YomediaFlash2URI_{{$data['zid']}} +'?expandTag='+expandTrack+"&clickTrack="+clickTrack+"&clickUrl="+clickUrl;

    swfobject.embedSWF(flashURI, "YomediaBalloonSidekickBannerExpand", "100%", "100%", "9.0.0", avlConfig.get('EI'), bL, bM, bN);
   
    closeYoMediaPopupAd_{{$data['zid']}}();
}


function setYoMediaPre_{{$data['zid']}}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectExpand('{{$wrapperAds}}', 'pre', sPos, eWidth_{{ $data['zid'] }}, eHeight_{{ $data['zid'] }}, iWidth_{{ $data['zid'] }}, iHeight_{{ $data['zid'] }});
}

function closeYoMediaPopupAd_{{$data['zid']}}() {
    avlInteractModule.closeAd('{{$wrapperAds}}', parseInt('900000'), 'showYoMediaPopupAd_{{$data['zid']}}');
}

showYoMediaPopupAd_{{$data['zid']}}(1);
