<?php
    $wrapperAds = 'YoMediaBalloon';
    $elAds = "YoMediaBalloon_".$data['zid'];
    $elWidth = $data['ad']->width;
    $elHeight = $data['ad']->height;
    $preExpandWidth = !empty($data['ad']->width_after) ? $data['ad']->width_after : $data['ad']->width;
    $preExpandHeight = !empty($data['ad']->height_after) ? $data['ad']->height_after : $data['ad']->height;

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
var flash_{!! $data['zid'] !!} = '{!! $data['ad']->source_url !!}';
var flash2_{!! $data['zid'] !!} = '{!! $data['ad']->source_url2 !!}';
var impressionTrack_{!! $data['zid'] !!} = encodeURIComponent("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!!  !empty($data['ovr']) ? "&ovr=1" : ''  !!}");
<?php if(!empty($thirdImpressionTrackArr)){ ?>
<?php foreach( $thirdImpressionTrackArr as $item ){ ?>
impressionTrack_{!! $data['zid'] !!} += '|'+encodeURIComponent("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
<?php } ?>
<?php } ?>


var clickTag_{!! $data['zid'] !!} = encodeURIComponent("{!!  TRACKER_URL  !!}track?evt=click&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&to={!! urlencode($data['ad']->destination_url) !!}&cs={!! $data['checksum'] !!}{!!  !empty($data['ovr']) ? "&ovr=1" : ''  !!}");
var clickTrack_{!! $data['zid'] !!}= "";

<?php if(!empty($thirdClickTrackArr)){ ?>
<?php $count = 0; ?>
<?php foreach( $thirdClickTrackArr as $item ){ ?>
<?php $count++; ?>
<?php if( $count == 1 ){ ?>
clickTrack_{!! $data['zid'] !!} += encodeURIComponent("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
<?php }else{ ?>
clickTrack_{!! $data['zid'] !!} += '|'+encodeURIComponent("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
<?php } ?>
<?php } ?>
<?php } ?>


var ff_{!! $data['zid'] !!} = flash_{!! $data['zid'] !!}+'?impression='+impressionTrack_{!! $data['zid'] !!}+"&clickTrack="+clickTrack_{!! $data['zid'] !!}+"&clickUrl="+clickTag_{!! $data['zid'] !!};
var ff2_{!! $data['zid'] !!} = flash2_{!! $data['zid'] !!} +'?impression='+impressionTrack_{!! $data['zid'] !!}+"&clickTrack="+clickTrack_{!! $data['zid'] !!}+"&clickUrl="+clickTag_{!! $data['zid'] !!};
function closeYoMediaExpand_{!! $data['zid'] !!}() {

document.getElementById("Yomedia_Full_Banner").innerHTML = "";
document.getElementById("backgroundId") ? (document.getElementById("backgroundId").style.width = "1px", document.getElementById("backgroundId").style.height = "1px", document.getElementById("backgroundId").style.backgroundImage = "") : (document.getElementById("tvcEx").style.width = "1px", document.getElementById("tvcEx").style.height = "1px");
document.body.style.overflow = "auto";
showYoMediaPopupAd_{!! $data['zid'] !!}(1);
}
function showYoMediaPopupAd_{!! $data['zid'] !!}(s) {
    var eWidth = parseInt('{!! $elWidth !!}');
    var eHeight = parseInt('{!! $elHeight !!}');
    var iWidth = parseInt('{!! $preExpandWidth !!}');
    var iHeight = parseInt('{!! $preExpandHeight !!}');
    var sPos = 'right-bottom';


    Default ='';

    avlInteractModule.initBalloon(
        '{!! $wrapperAds !!}','{!! $elAds !!}',iWidth,iHeight,eWidth,eHeight,ff_{!! $data['zid'] !!},Default,'VIB',parseInt('{!! $data['zid'] !!}'),'popup','top-down','{!! $data['ad']->flash_wmode !!}','VN', parseInt('1'),sPos
    );
    avlInteractModule.showBalloon('{!! $wrapperAds !!}', sPos, iWidth, iHeight, eWidth, eHeight, '{!! $elAds !!}', 'min', parseInt('30000'), parseInt('900000'));
    <?php $data['effect'] = 'Side_Kick';?>
    @include('ga_campaign')
}


function minYoMediaPopupAd_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectAd('{!! $wrapperAds !!}', 'top-down', sPos, 'min', parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $elWidth !!}'), parseInt('{!! $elHeight !!}'), 100);
}


function restoreYoMediaPopupAd_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectAd('{!! $wrapperAds !!}', 'top-down', sPos, 'max', parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $elWidth !!}'), parseInt('{!! $elHeight !!}'), 100);
}

function setYoMediaExpand_{!! $data['zid'] !!}() {
var a = document.getElementById("Yomedia_Full_Banner"), b = document.getElementById("backgroundId");
void 0 == a && (a = domManipulate.create('div','Yomedia_Full_Banner','border: 0px none; margin: 0px; padding: 0px; text-align: left; overflow: visible; position: fixed; z-index: 100000; top: 0px; left: 0px;',''), document.body.insertBefore(a, document.body.childNodes[0]));
void 0 == b && (b = domManipulate.create('div','backgroundId','border: 0px none; margin: 0px; padding: 0px; text-align: left; overflow: visible; position: fixed; z-index: 9999; top: 0px; left: 0px;',''),
document.body.insertBefore(b, document.body.childNodes[0]));
document.getElementById("backgroundId").setAttribute('onclick', 'closeYoMediaExpand_{!! $data['zid'] !!}()');
document.getElementById("backgroundId").style.width = "100%";
document.getElementById("backgroundId").style.height = "100%";
document.getElementById("backgroundId").style.backgroundImage = 'url("/demo/sidekick2/overlay.png")';
document.getElementById("Yomedia_Full_Banner").innerHTML = '<div id="Yomedia_flash_banner" style="border: 0 none;position: absolute;"></div>';
var e = document.getElementById("Yomedia_flash_banner");
var viewportwidth;
var viewportheight;
if (typeof window.innerWidth != 'undefined')
{
viewportwidth = window.innerWidth,
viewportheight = window.innerHeight
}
else if (typeof document.documentElement != 'undefined'
&& typeof document.documentElement.clientWidth !=
'undefined' && document.documentElement.clientWidth != 0)
{
viewportwidth = document.documentElement.clientWidth,
viewportheight = document.documentElement.clientHeight
}else{viewportwidth = document.getElementsByTagName('body')[0].clientWidth,viewportheight = document.getElementsByTagName('body')[0].clientHeight}
var left =  (viewportwidth - 980)/2;
e.style.top = "47px";
e.style.left = left + "px";
e.style.width = "980px";
e.style.height = "500px";
document.body.style.overflow = "hidden";
document.getElementById("Yomedia_flash_banner").innerHTML = '<object type="application/x-shockwave-flash"   width="100%" height="100%" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="file1" id="file1"><param value="'+ ff2_{!! $data['zid'] !!} +'" name="movie"><param value="high" name="quality"><param value="transparent" name="wmode"><param value="11.0.0.0" name="swfversion"><param value="false" name="allowFullScreen"><param value="always" name="allowScriptAccess"><embed name="file1" id="file1" width="100%" height="100%" align="middle" src="'+ ff2_{!! $data['zid'] !!} +'"  pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" quality="high"></embed></object>';
closeYoMediaPopupAd_{!! $data['zid'] !!}();
}


function setYoMediaPre_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectExpand('{!! $wrapperAds !!}', 'pre', sPos, parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $elWidth !!}'), parseInt('{!! $elHeight !!}'));
}

function closeYoMediaPopupAd_{!! $data['zid'] !!}() {
    avlInteractModule.closeAd('{!! $wrapperAds !!}', parseInt('900000'), 'showYoMediaPopupAd_{!! $data['zid'] !!}');
}

showYoMediaPopupAd_{!! $data['zid'] !!}(1);
@include("footer")