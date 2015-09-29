<?php
    $wrapperAds = 'YoMediaBalloon';
    $elAds = "YoMediaBalloon_".$data['zid'];
    $expandWidth = max($data['ad']->width_2, $data['ad']->width);
    $expandHeight = max($data['ad']->height_2, $data['ad']->height);
    $preExpandWidth  = $data['ad']->width_2 > 0 ? min($data['ad']->width_2, $data['ad']->width) : $data['ad']->width;
    $preExpandHeight = $data['ad']->height_2 > 0 ? min($data['ad']->height_2, $data['ad']->height) : $data['ad']->height;
    //Bar height
    $barHeight= !empty($data['ad']->bar_height) ? $data['ad']->bar_height : 150;

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
    $ovr = '';
    if (!empty($data['ovr'])) {
        $ovr = '&ovr=1';
    }
    $trackUrl = AD_SERVER_FILE;
    $destinationUrlEnCode = urlencode($data['ad']->destination_url);
    $impressionUrl = TRACKER_URL . "track?evt=impression&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&cs={$data['checksum']}{$ovr}";
    $clickTag = "{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}";
     
?>
	avlHelperModule.loadAvlStyle();
	function showYoMediaPopupAd_{!! $data['zid'] !!}() {
        var eWidth = parseInt('{!! $expandWidth !!}');
        var eHeight = parseInt('{!! $expandHeight !!}');
        var iWidth = parseInt('{!! $preExpandWidth !!}');
        var iHeight = parseInt('{!! $preExpandHeight !!}');
        var sPos = 'right-bottom';
    
        var flash = '{!! $data['ad']->source_url !!}';
        var impressionTrack = encodeURIComponent("{!! $impressionUrl !!}");    
    
        @if (!empty($thirdImpressionTrackArr))
            @foreach( $thirdImpressionTrackArr as $item )
                impressionTrack += '|'+encodeURIComponent("{!! trim(str_replace('[timestamp]', time(), $item))!!}");
            @endforeach
        @endif
    
    
        var clickTag = encodeURIComponent("{!! $clickTag !!}");
        var clickTrack= "";
    
    @if(!empty($thirdClickTrackArr))
        <?php $count = 0; ?>
        @foreach( $thirdClickTrackArr as $item )
            <?php $count++; ?>
            @if( $count == 1 )
                clickTrack += encodeURIComponent("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
            @else
                clickTrack += '|'+encodeURIComponent("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
            @endif
        @endforeach
    @endif
    
    var ff = flash;
    
    var flashvar = {
            zid : {!!  $data['zid']  !!},
            clickTrack : clickTrack,
            clickUrl : clickTag,
            urlVideo : '{!! !empty($data['ad']->source_url2) ? $data['ad']->source_url2 : '' !!}'
        }
    
        Default ='';
    
        avlInteractModule.initBalloon(
            '{!! $wrapperAds !!}','{!! $elAds !!}',iWidth,iHeight,eWidth,eHeight,ff,Default,'VIB',parseInt('{!! $data['zid'] !!}'),'popup','top-down','{!! $data['ad']->flash_wmode !!}',0,0,0, flashvar
        );
        avlInteractModule.showBalloon('{!! $wrapperAds !!}', sPos, iWidth, iHeight, eWidth, eHeight, '{!! $elAds !!}', 'min', parseInt('30000'), parseInt('900000'));
    }
@if ('html' != $data['ad']->ad_type)
    
    showYoMediaPopupAd_{!! $data['zid'] !!}();
@else
	String.prototype.VwIx=function(s){return this.toLowerCase().indexOf(s.toLowerCase())}
    String.prototype.VwHas=function(){for(var i=0;i<arguments.length;i++)if(this.VwIx(arguments[i])>-1)return true;return false;}
    
    window.VwAg=navigator.userAgent.toLowerCase();
    window.VwDopr=VwAg.VwHas("opera");
    window.VwDIE=VwAg.VwHas("msie")&&!VwDopr;
    window.VwIE9=VwAg.VwHas("msie 9");
    window.VwAnimateStartTime;
    window.VwAnimateTime = 2000;
    window.VwAnimateInterval=null;
    window.VwDistanceToScroll=0;
    
	<?php 
	    $eidtype = substr($data['element_id'],0,1);
        $eid = substr($data['element_id'],1);
        $destinationUrl = $data['ad']->destination_url;
        $destinationUrlEnCode = urlencode($destinationUrl);
        $clickTag = "{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}";
        $clickTagEnCode = urlencode("{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}");
        $clickTagEnCodeBeacon = urlencode("{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&bc=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}");
        $htmlSource = preg_replace('/\s\s+/', '', $data['ad']->html_source);
        $htmlSource = str_replace('[yomedia_maincontain]', $eid, $htmlSource);
        $htmlSource = str_replace('[yomedia_zone_id]', $data['zid'], $htmlSource);
        $htmlSource = str_replace('[yomedia_publisher_domain]', $data['publisher_domain'], $htmlSource);
        $displayType = isset($data['ad']->display_type) ? $data['ad']->display_type : '';
        $htmlSource = trim(str_replace('[yomedia_click_url]', $clickTag, $htmlSource));
        $htmlSource = trim(str_replace('[yomedia_click_url_encode]', $clickTagEnCode, $htmlSource));
        
        $clickTrackEnCode = '';
        if(!empty($thirdClickTrackArr)){
            $count = 0;            
            $clickTrack = '';
            foreach( $thirdClickTrackArr as $item ){
                $count++;
                if($count == 1){
                    $clickTrackEnCode .= urlencode(trim(str_replace('[timestamp]', time(), $item)));
                    $clickTrack .= trim(str_replace('[timestamp]', time(), $item));
                } else {
                    $clickTrackEnCode .= '|' . urlencode(trim(str_replace('[timestamp]', time(), $item)));
                    $clickTrack .= '|' . trim(str_replace('[timestamp]', time(), $item));
                }
            }
            
            $htmlSource = str_replace('[yomedia_third_click_url_encode]', $clickTrackEnCode, $htmlSource);
            $htmlSource = str_replace('[yomedia_third_click_url]', $clickTrackEnCode, $htmlSource);
        } 
	?>
	
	avlInteractModule.innerHTMLAds('{!! $data['zid'] !!}', '{!! addslashes($htmlSource) !!}', '{!! $displayType !!}');
    avlHelperModule.embedTracking("{!! $impressionUrl !!}");
    <?php $data['effect'] = 'Balloon'; ?>
    @include('ga_campaign')
    @if(!empty($thirdImpressionTrackArr))
        @foreach( $thirdImpressionTrackArr as $item )
            avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
        @endforeach
    @endif
@endif
//Minimize popup
function minYoMediaPopupAd_{!! $data['zid'] !!}() {
    //var sPos = 'right-bottom';
    //avlInteractModule.rectAd('{!! $wrapperAds !!}', 'top-down', sPos, 'min', parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $expandWidth !!}'), parseInt('{!! $expandHeight !!}'), parseInt('{!! $barHeight !!}'));
    closeYoMediaExpand_{!! $data['zid'] !!}();
}

function restoreYoMediaPopupAd_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectAd('{!! $wrapperAds !!}', 'top-down', sPos, 'max', parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $expandWidth !!}'), parseInt('{!! $expandHeight !!}'), 100);
}

function setYoMediaExpand_{!! $data['zid'] !!}() {
    //var sPos = 'right-bottom';
    //avlInteractModule.rectExpand('{!! $wrapperAds !!}', 'ex', sPos, parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $expandWidth !!}'), parseInt('{!! $expandHeight !!}'));
    var a = document.getElementById("Yomedia_Full_Banner"), b = document.getElementById("backgroundId");
    void 0 == a && (a = domManipulate.create('div','Yomedia_Full_Banner','border: 0px none; margin: 0px; padding: 0px; text-align: left; overflow: visible; position: absolute; z-index: 10000; top: 3%; left: 0px; height: 201px;',''), document.body.insertBefore(a, document.body.childNodes[0]));
    document.getElementById("Yomedia_Full_Banner").innerHTML = '<div id="Yomedia_flash_banner" style="border: 0px none; position: absolute; left:1349px; top: 0%; bottom: 0px;"></div>';
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
    e.style.width = viewportwidth +"px";
    var height_{!! $data['zid'] !!} = viewportheight;
    document.getElementById("Yomedia_flash_banner").innerHTML = '<div style="text-align:center;width:1349px;"><object type="application/x-shockwave-flash"   width="100%" height="'+height_{!! $data['zid'] !!}+'"  classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="file1" id="file1"><param value="{!! $data['ad']->source_url2 !!}?zid={!! $data['zid'] !!}" name="movie"><param value="high" name="quality"><param value="transparent" name="wmode"><param value="11.0.0.0" name="swfversion"><param name="flashvars" value="zid={!! $data['zid'] !!}&clickTrack={!! $clickTrackEnCode !!}&clickUrl={!! $clickTagEnCode !!}"><param value="false" name="allowFullScreen"><param value="always" name="allowScriptAccess"><embed name="file1" id="file1" width="100%" height="'+height_{!! $data['zid'] !!}+'" align="middle" src="{!! $data['ad']->source_url2 !!}?zid={!! $data['zid'] !!}&clickUrl={!! $clickTagEnCodeBeacon !!}"  pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" quality="high"></embed></object></div>';
    closeYoMediaPopupAd_{!! $data['zid'] !!}();
    animateExpand();
}


function setYoMediaPre_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectExpand('{!! $wrapperAds !!}', 'pre', sPos, parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $expandWidth !!}'), parseInt('{!! $expandHeight !!}'), 50);
}

function closeYoMediaPopupAd_{!! $data['zid'] !!}() {
    avlInteractModule.closeAd('{!! $wrapperAds !!}', parseInt('900000'), 'showYoMediaPopupAd_{!! $data['zid'] !!}');
    
}

function closeYoMediaExpand_{!! $data['zid'] !!}() {
    window.VwAnimateStartTime=new Date();
    clearInterval(VwAnimateInterval);
    window.VwAnimateInterval = setInterval("reshowYoMediaExpand_{!! $data['zid'] !!}();",15);
    reshowYoMediaExpand_{!! $data['zid'] !!}();
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
	window.open(clickTag);
}

function animateExpand(){
//	var sdiv = document.getElementById("sidekickDiv");
//	if(sdiv)sdiv.style.display="block";

    //acount for scrollbar size on ie9 browsers
    if(VwIE9)document.documentElement.scrollLeft+=16;

    //get the current time and use this as the animation start time
    window.VwAnimateStartTime=new Date();

    //clear any current intervals related to animating
    clearInterval(window.VwAnimateInterval);

    //start expand animation
    window.VwAnimateInterval = setInterval("VwScrollToRight();",5);
    VwScrollToRight();
}

//easing
function VwEasing(t, b, c, d){
    t = t/(d/2);
    if (t < 1) return c/2*t*t + b;
    t--;
    return -c/2 * (t*(t-2) - 1) + b;
}

//get current Y scrollbar location
function getScrollXY() {
    var scrOfX = 0, scrOfY = 0;
    if( typeof( window.pageYOffset ) == 'number' ) {
        //Netscape compliant
        scrOfY = window.pageYOffset;
        scrOfX = window.pageXOffset;
    } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
        //DOM compliant
        scrOfY = document.body.scrollTop;
        scrOfX = document.body.scrollLeft;
    } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
        //IE6 standards compliant mode
        scrOfY = document.documentElement.scrollTop;
        scrOfX = document.documentElement.scrollLeft;
    }
    return scrOfY;
}

//this function is called by animateExpand() to start expand easing animation
function VwScrollToRight(){
    try{
        var VwCurrentTime = new Date();
        var VwTimePassed = VwCurrentTime.getTime() - window.VwAnimateStartTime.getTime();
        window.VwDistanceToScroll = document.body.scrollWidth;

        //the animation will continue to ease until the specified animation time.
        if(VwTimePassed< window.VwAnimateTime){
            var VwScrollAmount = VwEasing(VwTimePassed, 0,window.VwDistanceToScroll, window.VwAnimateTime);
            window.scrollTo(VwScrollAmount,getScrollXY());
        }else{

            //once the animation completes, clear all animation intervals
            clearInterval(window.VwAnimateInterval);

        }
    }catch(e) {}
}

function reshowYoMediaExpand_{!! $data['zid'] !!}() {
    try{
        var VwCurrentTime = new Date();
        var VwTimePassed = VwCurrentTime.getTime() - window.VwAnimateStartTime.getTime();
        window.VwDistanceToScroll = document.body.scrollWidth;

        if(VwTimePassed< window.VwAnimateTime){
            var VwScrollAmount = VwEasing(VwTimePassed, 0,window.VwDistanceToScroll, window.VwAnimateTime);
            window.scrollTo(window.VwDistanceToScroll-VwScrollAmount+10,getScrollXY());
        }else{
            var sdiv = document.getElementById("sidekickDiv");
            if(sdiv)sdiv.style.display="none";
            clearInterval(window.VwAnimateInterval);
            window.scrollTo(0,getScrollXY());
            document.getElementById("Yomedia_Full_Banner").innerHTML = "";
            document.body.style.overflow = "auto";
            showYoMediaPopupAd_{!! $data['zid'] !!}();
        }
    }catch(e) {}
}

function create(a) {
    var b = document.createDocumentFragment(), c = document.createElement("div");
    for (c.innerHTML = a; c.firstChild;)b.appendChild(c.firstChild);
    return b
}


@if($data['aid'] == 151 || strpos($data['ad']->source_url2, '.mp4') !== FALSE || strpos($data['ad']->source_url2, '.flv') !== FALSE )
<?php
$eventArr = [
    'start','firstQuartile','midpoint','thirdQuartile','complete'
];
$thirdPartyTrackings = [];
$data['ad']->third_party_tracking = json_decode($data['ad']->third_party_tracking, 1);
if(!empty($data['ad']->third_party_tracking) && is_array($data['ad']->third_party_tracking) && count($data['ad']->third_party_tracking)){
    foreach ($data['ad']->third_party_tracking as $tracker) {
        $thirdPartyTrackings[$tracker['event']] = $tracker['url'];
    }
}
?>
@foreach ($eventArr as $event)
function {!! $event !!}YomediaVideo_{{ $data['zid'] }}(){
    avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt={!! $event !!}&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!!  !empty($data['ovr']) ? "&ovr=1" : ''  !!}");
    @if(!empty($thirdPartyTrackings[$event]))
    avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $thirdPartyTrackings[$event])) !!}");
    @endif
}
@endforeach
@endif

@include("footer")