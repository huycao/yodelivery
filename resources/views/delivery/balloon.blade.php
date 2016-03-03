@include("footer")
@include("url_track_ga")
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

@if ('html' != $data['ad']->ad_type)
    avlHelperModule.loadAvlStyle();
    
    function showYoMediaPopupAd_{!! $data['zid'] !!}(s) {
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
    if (typeof _YoClick != 'undefined' && avlHelperModule.validateUrl(_YoClick)) {
    	if ("" != clickTrack) {
    		clickTrack += '|'+encodeURIComponent(_YoClick);
    	} else {
    		clickTrack += encodeURIComponent(_YoClick);
    	}
    }
    
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
    
        avlHelperModule.embedTracking("{!! $impressionUrl !!}");
    
        @if(!empty($thirdImpressionTrackArr))
            @foreach( $thirdImpressionTrackArr as $item )
                avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
            @endforeach
        @endif
        if (typeof _YoImp != 'undefined' && avlHelperModule.validateUrl(_YoImp)) {
        	avlHelperModule.embedTracking(_YoImp);
        }
        <?php $data['effect'] = 'Balloon'; ?>
        @include('ga_campaign')
    }
    
    showYoMediaPopupAd_{!! $data['zid'] !!}(1);
@else
	<?php 
	    $eidtype = substr($data['element_id'],0,1);
        $eid = substr($data['element_id'],1);
        $destinationUrl = $data['ad']->destination_url;
        $destinationUrlEnCode = urlencode($destinationUrl);
        $clickTag = "{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}";
        $clickTagEnCode = urlencode("{$trackUrl}track?evt=click&aid={$data['aid']}&fpid={$data['fpid']}&zid={$data['zid']}&rt=1&to={$destinationUrlEnCode}&cs={$data['checksum']}{$ovr}");
        $htmlSource = preg_replace('/\s\s+/', '', $data['ad']->html_source);
        $htmlSource = str_replace('[yomedia_maincontain]', $eid, $htmlSource);
        $htmlSource = str_replace('[yomedia_zone_id]', $data['zid'], $htmlSource);
        $htmlSource = str_replace('[yomedia_publisher_domain]', $data['publisher_domain'], $htmlSource);
        $displayType = isset($data['ad']->display_type) ? $data['ad']->display_type : '';
        $htmlSource = trim(str_replace('[yomedia_click_url]', $clickTag, $htmlSource));
        $htmlSource = trim(str_replace('[yomedia_click_url_encode]', $clickTagEnCode, $htmlSource));
        
        if(!empty($thirdClickTrackArr)){
            $count = 0;
            $clickTrackEnCode = '';
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
            $htmlSource = str_replace('[yomedia_third_click_url]', $clickTrack, $htmlSource);
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
    if (typeof _YoImp != 'undefined' && avlHelperModule.validateUrl(_YoImp)) {
    	avlHelperModule.embedTracking(_YoImp);
    }
    
@endif
@if ('' != $data['ad']->fb_like)
    avlHelperModule.embedFBLike('{!! $data['ad']->fb_like !!}', 0, 0, {!! $data['ad']->fb_margin_right !!}, {!! $data['ad']->fb_margin_bottom !!});
@endif

//Minimize popup
function minYoMediaPopupAd_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectAd('{!! $wrapperAds !!}', 'top-down', sPos, 'min', parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $expandWidth !!}'), parseInt('{!! $expandHeight !!}'), parseInt('{!! $barHeight !!}'));
}

function restoreYoMediaPopupAd_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectAd('{!! $wrapperAds !!}', 'top-down', sPos, 'max', parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $expandWidth !!}'), parseInt('{!! $expandHeight !!}'), 100);
}

function setYoMediaExpand_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectExpand('{!! $wrapperAds !!}', 'ex', sPos, parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $expandWidth !!}'), parseInt('{!! $expandHeight !!}'));
}

function setYoMediaPre_{!! $data['zid'] !!}() {
    var sPos = 'right-bottom';
    avlInteractModule.rectExpand('{!! $wrapperAds !!}', 'pre', sPos, parseInt('{!! $preExpandWidth !!}'), parseInt('{!! $preExpandHeight !!}'), parseInt('{!! $expandWidth !!}'), parseInt('{!! $expandHeight !!}'), 50);
}

function closeYoMediaPopupAd_{!! $data['zid'] !!}() {
    avlInteractModule.closeAd('{!! $wrapperAds !!}', parseInt('900000'), 'showYoMediaPopupAd_{!! $data['zid'] !!}');
}

function setYomediaFBLike_{!! $data['zid'] !!}(r, b, mr, mb) {
    avlHelperModule.controlFBLike(r, b, mr, mb);
}

function showYomediaFBLike_{!! $data['zid'] !!}() {
    avlHelperModule.showFBLike();
}

function removeYomediaFBLike_{!! $data['zid'] !!}() {
    avlHelperModule.removeFBLike();
}

function hideYomediaFBLike_{!! $data['zid'] !!}() {
    avlHelperModule.hideFBLike();
}

function clickTrackingYomedia_{!! $data['zid'] !!}() {
    @include("ga_click")
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
	var opened = window.open(clickTag, '_blank');
    if (typeof opened == 'undefined') {
        tagLink = document.createElement('a');       
        tagLink.setAttribute('id', 'yomedia-tag-link-{!! $data['zid'] !!}');
        tagLink.setAttribute('href', clickTag);
        tagLink.setAttribute('target', '_blank');
        tagLink.style.display = 'none';
        document.body.appendChild(tagLink);
        fireEventYomedia_{!! $data['zid'] !!}(document.getElementById("yomedia-tag-link-{!! $data['zid'] !!}"), "click");
    }
}

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

function addAnEventListener_{!! $data['zid'] !!}(obj,evt,func){
    if ('addEventListener' in obj){
        obj.addEventListener(evt,func, false);
    } else if ('attachEvent' in obj){//IE
        obj.attachEvent('on'+evt,func);
    }
}

function iFrameListener_{!! $data['zid'] !!}(event){
     fn_{!! $data['zid'] !!} = event.data;
     if ('string' == typeof fn_{!! $data['zid'] !!} && fn_{!! $data['zid'] !!}.toLowerCase().indexOf("yomedia") >= 0) {
    	 eval(fn_{!! $data['zid'] !!});
     }
}

function fireEventYomedia_{!! $data['zid'] !!}(node, eventName) {
    // Make sure we use the ownerDocument from the provided node to avoid cross-window problems
    var doc;
    if (node.ownerDocument) {
        doc = node.ownerDocument;
    } else if (node.nodeType == 9){
        // the node may be the document itself, nodeType 9 = DOCUMENT_NODE
        doc = node;
    } else {
        throw new Error("Invalid node passed to fireEvent: " + node.id);
    }

     if (node.dispatchEvent) {
        // Gecko-style approach (now the standard) takes more work
        var eventClass = "";

        // Different events have different event classes.
        // If this switch statement can't map an eventName to an eventClass,
        // the event firing is going to fail.
        switch (eventName) {
            case "click": // Dispatching of 'click' appears to not work correctly in Safari. Use 'mousedown' or 'mouseup' instead.
            case "mousedown":
            case "mouseup":
                eventClass = "MouseEvents";
                break;

            case "focus":
            case "change":
            case "blur":
            case "select":
                eventClass = "HTMLEvents";
                break;

            default:
                throw "fireEvent: Couldn't find an event class for event '" + eventName + "'.";
                break;
        }
        var event = doc.createEvent(eventClass);

        var bubbles = eventName == "change" ? false : true;
        event.initEvent(eventName, bubbles, true); // All events created as bubbling and cancelable.

        event.synthetic = true; // allow detection of synthetic events
        node.dispatchEvent(event, true);
    } else  if (node.fireEvent) {
        // IE-old school style
        var event = doc.createEventObject();
        event.synthetic = true; // allow detection of synthetic events
        node.fireEvent("on" + eventName, event);
    }
}
var fn_{!! $data['zid'] !!}='';
addAnEventListener_{!! $data['zid'] !!}(window,'message',iFrameListener_{!! $data['zid'] !!});