<?php
$source          = "";
$destination_url = $data['ad']->destination_url;
$source          = $data['ad']->source_url;
$height          = $data['ad']->height;
$width           = $data['ad']->width;
$eidtype         = substr($data['element_id'],0,1);
$eid             = substr($data['element_id'],1);
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
function showYoMediaPopupAd_{!! $data['zid'] !!}(s) {

    var clickTag = encodeURIComponent("{!! URL::to('/') !!}/track?evt=click&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&to={!! urlencode($data['ad']->destination_url) !!}&cs={!! $data['checksum'] !!}");
   
    var a_{!! $data['zid'] !!} = document.getElementById("YomediaInpage_{!! $data['zid'] !!}");
    if(a_{!! $data['zid'] !!} == null) {
        @if($eidtype == '#')
        var content_{!! $data['zid'] !!} = document.getElementById('{!! $eid !!}');
        @else
        var content_{!! $data['zid'] !!} = document.getElementsByClassName('{!! $eid !!}')[0];
        @endif
        e_{!! $data['zid'] !!} = content_{!! $data['zid'] !!}.childNodes;
        var p_{!! $data['zid'] !!} = 0;
        for(var i_{!! $data['zid'] !!}=0; i_{!! $data['zid'] !!} < e_{!! $data['zid'] !!}.length; i_{!! $data['zid'] !!}++) {
            if(e_{!! $data['zid'] !!}[i_{!! $data['zid'] !!}].clientHeight > 0){
                p_{!! $data['zid'] !!} = p_{!! $data['zid'] !!}+ e_{!! $data['zid'] !!}[i_{!! $data['zid'] !!}].clientHeight;
            }
            
            if(p_{!! $data['zid'] !!} >= (content_{!! $data['zid'] !!}.clientHeight / 2)){
                if(typeof(content_{!! $data['zid'] !!}.childNodes[i_{!! $data['zid'] !!} +1]) != 'undefined'){
                    var eleToInsert = i_{!! $data['zid'] !!} +1;
                }
                else{
                    var eleToInsert = i_{!! $data['zid'] !!};
                }

                a_{!! $data['zid'] !!} = domManipulate.create('div', 'YomediaInpage_{!! $data['zid'] !!}', '', ''), content_{!! $data['zid'] !!}.insertBefore(a_{!! $data['zid'] !!}, content_{!! $data['zid'] !!}.childNodes[eleToInsert]);
                break;
            }
        }
    }

    if(a_{!! $data['zid'] !!} == null){
        return false;
    }
    
    var container_width = content_{!! $data['zid'] !!}.clientWidth;
    a_{!! $data['zid'] !!}.style.opacity = 1;
    a_{!! $data['zid'] !!}.style.position = 'relative';
    a_{!! $data['zid'] !!}.style.overflow = 'hidden';
    a_{!! $data['zid'] !!}.style.zIndex  = '0';
    a_{!! $data['zid'] !!}.style.width = screen.width + "px";
    a_{!! $data['zid'] !!}.style.display = 'block';
    a_{!! $data['zid'] !!}.style.visibility = 'visible';
    a_{!! $data['zid'] !!}.style.height =  (container_width / {!! $width !!} * {!! $height !!} + 40) +'px';
    a_{!! $data['zid'] !!}.style.background = 'transparent';
    var rs = '';
    if(avlInteractModule.isMobile() == true){
    rs = '<div id="YomediaInpageContent_{!! $data['zid'] !!}" style="display: block; opacity: 1; overflow: hidden; margin: 0px auto; position: fixed; z-index: 1; bottom: 20%; left:0;right:0;max-width: 100%;background: transparent;"><a style="display: block;" href="'+decodeURIComponent(clickTag)+'" target="_blank" id="yomedia-destination-{!! $data['zid'] !!}"><img id="yomedia-inpage-banner" style="width:100%; max-width: 100%;" src="{!! $source !!}"></a></div>';
    rs += '<div id="more-view_{!! $data['zid'] !!}" style="opacity: 1; float: right; z-index: 3; clear: both; position: absolute; bottom: 0px; left: 0px; width: 100%; text-align: center; background: transparent;height: 30px;"><a href="#" target="_blank" style="color:#FFF;font-size: 1.3em;background: #CCC;padding: 5px 13px;border-radius: 10px;margin: 5px;height: 18px;">Đọc tiếp</a></div>';
    domManipulate.getElid('YomediaInpage_{!! $data['zid'] !!}').innerHTML = rs;
    domManipulate.getElid('YomediaInpage_{!! $data['zid'] !!}').style.display = 'block';
    

    avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!!  !empty($data['ovr']) ? "&ovr=1" : ''  !!}");
    <?php $data['effect'] = 'Inpage';?>
    @include('ga_campaign')
    @if(!empty($thirdImpressionTrackArr))
        @foreach( $thirdImpressionTrackArr as $item )
            avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
        @endforeach
    @endif

    }else {
    domManipulate.getElid('YomediaInpage_{!! $data['zid'] !!}').innerHTML = rs;
    domManipulate.getElid('YomediaInpage_{!! $data['zid'] !!}').style.display = 'none';
    }
}


window.scroller = function() {
    var x = 0;
    var y = 0;
    if(typeof window.pageYOffset == "number") {
        y = window.pageYOffset;
        x = window.pageXOffset;
    }else if(document.body && (document.body.scrollLeft || document.body.scrollTop)) {
        y = document.body.scrollTop;
        x = document.body.scrollLeft;
    }else if(document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
        y = document.documentElement.scrollTop;
        x = document.documentElement.scrollLeft;
    }
    return {x:x, y:y};
}

function getOffsetY_{!! $data['zid'] !!}(obj) {
    var y = 0;
    if(obj.offsetParent) {
        while(obj) {
            y += obj.offsetTop;
            obj = obj.offsetParent;
        }
    }else if(obj.y) {
        y += obj.y;
    }
    return y;
}


function getViewPortHeight_{!! $data['zid'] !!}() {
    var de = document.documentElement;

    if(!!window.innerWidth)
    { return window.innerHeight; }
    else if( de && !isNaN(de.clientHeight) )
    { return de.clientHeight; }

    return 0;
}


function seenByViewport_{!! $data['zid'] !!}(obj) {
    var vpH           = getViewPortHeight_{!! $data['zid'] !!}(),
        windowY       = window.scroller().y, // Scroll Top
        elementY      = getOffsetY_{!! $data['zid'] !!}(obj);
        elementHeight = obj.clientHeight;
        ybottom       = elementY+elementHeight;
        endViewPort = vpH + windowY;

    if ( (ybottom > windowY && ybottom < endViewPort) || (elementY > windowY && elementY < endViewPort) ) {
        return true;
    }
    else
    {
        return false;
    }
}

document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        showYoMediaPopupAd_{!! $data['zid'] !!}(1);

        window.onclick = function(event) {
            var checkID = 'yomedia-destination-{!! $data['zid'] !!}';
            var checkID2 = 'yomedia-inpage-banner';
            if (checkID == event.target.id || checkID2 == event.target.id) {
            @if(!empty($thirdClickTrackArr))
                @foreach( $thirdClickTrackArr as $item )
                    avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
                @endforeach
            @endif
            }
        };
    

        window.onscroll = function(){
            var adContainer = document.getElementById('YomediaInpage_{!! $data['zid'] !!}');
            if(adContainer != null){
                if(seenByViewport_{!! $data['zid'] !!}(adContainer)){
                    document.getElementById('yomedia-destination-{!! $data['zid'] !!}').style.display = 'block';
                }
                else{
                    document.getElementById('yomedia-destination-{!! $data['zid'] !!}').style.display = 'none';
                }
            }
        }


    }
}
@include("footer")
