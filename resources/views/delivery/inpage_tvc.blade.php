@include("url_track_ga")
<?php
$source          = "";
$destination_url = $data['ad']->destination_url;
$source          = $data['ad']->source_url;
$height          = $data['ad']->height;
$width           = $data['ad']->width;
$eidtype         = substr($data['element_id'],0,1);
$eid             = substr($data['element_id'],1);
if (!empty($data['ad']->destination_url)) {
    $data['ad']->destination_url = trim(str_replace('[timestamp]', time(), $data['ad']->destination_url));
}
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

avlHelperModule.embedJs('http://imasdk.googleapis.com/js/sdkloader/ima3.js');

var YomediaInpageWrapper_{!! $data['zid'] !!}     = "YomediaInpageWrapper_{!! $data['zid'] !!}";
var YomediaInpageAD_{!! $data['zid'] !!}          = "YomediaInpageAd_{!! $data['zid'] !!}";
var YomediaInpagePlayer_{!! $data['zid'] !!}      = "YomediaInpagePlayer_{!! $data['zid'] !!}";
var YomediaInpageAdContainer_{!! $data['zid'] !!} = "YomediaInpageAdContainer_{!! $data['zid'] !!}";
var YomediaInpagePosterTagH_{!! $data['zid'] !!}  = "YomediaInpagePosterH_{!! $data['zid'] !!}";
var YomediaInpagePosterTagW_{!! $data['zid'] !!}  = "YomediaInpagePosterW_{!! $data['zid'] !!}";
var YomediaInpagePosterH_{!! $data['zid'] !!}     = "{!! $data['ad']->source_url2 !!}";
var YomediaInpagePosterW_{!! $data['zid'] !!}     = YomediaInpagePosterH_{!! $data['zid'] !!};
var PathLength = YomediaInpagePosterH_{!! $data['zid'] !!}.length;
var FilenameH_{!! $data['zid'] !!} = YomediaInpagePosterH_{!! $data['zid'] !!}.replace(/^.*[\\\/]/, '');
var FileLength = FilenameH_{!! $data['zid'] !!}.length;

var url_{!! $data['zid'] !!} = YomediaInpagePosterH_{!! $data['zid'] !!}.substring(0,PathLength-FileLength);

var arrTmp = FilenameH_{!! $data['zid'] !!}.split('.');
if (arrTmp.length > 1) {
	arrTmp[0] += '_w';
	YomediaInpagePosterW_{!! $data['zid'] !!} = url_{!! $data['zid'] !!} + arrTmp.join('.');
}
if (parseInt({!! $height !!})) {
	var YomediaInpageRatio_{!! $data['zid'] !!}       = {!! $width !!}/{!! $height !!};
} else {
	var YomediaInpageRatio_{!! $data['zid'] !!}       = 0.5;
}
var PublisherContainer_{!! $data['zid'] !!}       = '';
var ad_{!! $data['zid'] !!}                       = '';
var container_width_{!! $data['zid'] !!}          = 0;
var container_height_{!! $data['zid'] !!}         = 0;
function showYoMediaPopupAd_{!! $data['zid'] !!}(s) {
    if(!avlInteractModule.isMobile()){
        return false;
    }

    var postionIE_{!! $data['zid'] !!} = 'fixed';
    if(avlInteractModule.isWindowPhone()){
    postionIE_{!! $data['zid'] !!} = 'relative';
    }
    var clickTag = encodeURIComponent("{!! URL::to('/') !!}/track?evt=click&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&to={!! urlencode($data['ad']->destination_url) !!}&cs={!! $data['checksum'] !!}");

    ad_{!! $data['zid'] !!} = document.getElementById(YomediaInpageAD_{!! $data['zid'] !!});
    if(ad_{!! $data['zid'] !!} == null) {
        @if($eidtype == '#')
        PublisherContainer_{!! $data['zid'] !!} = document.getElementById('{!! $eid !!}');
        @else
        PublisherContainer_{!! $data['zid'] !!} = document.getElementsByClassName('{!! $eid !!}')[0];
        @endif
        var bannerHTag = '<a href="'+decodeURIComponent(clickTag)+'" target="_blank"><img id="'+YomediaInpagePosterTagH_{!! $data['zid'] !!}+'" src="'+YomediaInpagePosterH_{!! $data['zid'] !!}+'"/></a>';
       var bannerWTag = '<a href="'+decodeURIComponent(clickTag)+'" target="_blank"><img id="'+YomediaInpagePosterTagW_{!! $data['zid'] !!}+'" src="'+YomediaInpagePosterW_{!! $data['zid'] !!}+'"/></a>';
        var videoTag = '<video style="opacity:0" id="'+YomediaInpagePlayer_{!! $data['zid'] !!}+'" poster = "'+YomediaInpagePosterH_{!! $data['zid'] !!}+'" controls preload="auto"'+
            'width="1" height="1">'+
            '<source src="" type="video/mp4" />'+
            '</video>';
        if(window.screen.height >= window.screen.width) {
            container_width_{!! $data['zid'] !!} = PublisherContainer_{!! $data['zid'] !!}.clientWidth - (PublisherContainer_{!! $data['zid'] !!}.clientWidth * 0.1);
            container_height_{!! $data['zid'] !!} = container_width_{!! $data['zid'] !!} / YomediaInpageRatio_{!! $data['zid'] !!};
        }else{
            container_width_{!! $data['zid'] !!} = PublisherContainer_{!! $data['zid'] !!}.clientWidth - (PublisherContainer_{!! $data['zid'] !!}.clientWidth * 0.6);
            container_height_{!! $data['zid'] !!} = container_width_{!! $data['zid'] !!} / YomediaInpageRatio_{!! $data['zid'] !!};
        }
        ad_{!! $data['zid'] !!} = domManipulate.create('div', YomediaInpageAD_{!! $data['zid'] !!}, 'position:relative;overflow: hidden;display:block; width:100%;-moz-transform: scale(1);-moz-transform-origin: 0 0;', '<div id="YomediaInpageContent_561" style="padding:0;display: block; opacity: 1; overflow: hidden; margin: 0px auto; position: '+postionIE_{!! $data['zid'] !!}+'; z-index: 1; top: 0%; left:0;right:0;max-width: 100%;background: transparent;">'+ bannerHTag + bannerWTag + videoTag + '<div style="margin: 0 auto !important" id="'+YomediaInpageAdContainer_{!! $data['zid'] !!}+'"></div></div>');
        var winHeight = window.screen.height;
        var winWidth = window.screen.width;
        ad_{!! $data['zid'] !!}.style.opacity = 1;
        ad_{!! $data['zid'] !!}.style.position = 'relative';
        ad_{!! $data['zid'] !!}.style.overflow = 'hidden';
        ad_{!! $data['zid'] !!}.style.zIndex  = '0';
        ad_{!! $data['zid'] !!}.style.width = "100%";
        ad_{!! $data['zid'] !!}.style.display = 'block';
        ad_{!! $data['zid'] !!}.style.visibility = 'visible';
        ad_{!! $data['zid'] !!}.style.height =  winHeight+'px';
        ad_{!! $data['zid'] !!}.addEventListener("click", requestAds);
        appendToMiddle(ad_{!! $data['zid'] !!}, PublisherContainer_{!! $data['zid'] !!});

        var posterH = document.getElementById(YomediaInpagePosterTagH_{!! $data['zid'] !!});
        posterH.style.maxWidth =  '92%';
        posterH.style.height =  'auto';
        posterH.style.padding =  '4%';

        var posterW = document.getElementById(YomediaInpagePosterTagW_{!! $data['zid'] !!});
        posterW.style.maxWidth =  '92%';
        posterW.style.height =  'auto';
        posterW.style.padding =  '4%';

        var playerWrapper = document.getElementById(YomediaInpageAdContainer_{!! $data['zid'] !!});
        playerWrapper.style.position = 'absolute';

        resizeAds_{!! $data['zid'] !!}();
        init(YomediaInpagePlayer_{!! $data['zid'] !!});
        var YomediaStyleVideo_{!! $data['zid'] !!} = domManipulate.create('div', "YomediaStyleVideo_{!! $data['zid'] !!}","",'<style id="YomediaStyleVideo_{!! $data['zid'] !!}">#YomediaInpageAdContainer_{!! $data['zid'] !!} video{min-height: '+container_height_{!! $data['zid'] !!}+'px;max-height: '+container_height_{!! $data['zid'] !!}+'px;}</style>');
        ad_{!! $data['zid'] !!}.appendChild(YomediaStyleVideo_{!! $data['zid'] !!});

        avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!!  !empty($data['ovr']) ? "&ovr=1" : ''  !!}");
    	<?php $data['effect'] = 'Inpage_TVC';?>
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
function resizeAds_{!! $data['zid'] !!}() {
    var ad_{!! $data['zid'] !!} = document.getElementById(YomediaInpageAD_{!! $data['zid'] !!});
    ad_{!! $data['zid'] !!}.style.height =  window.screen.height+'px';

    var playerWrapper = document.getElementById(YomediaInpageAdContainer_{!! $data['zid'] !!});
    var posterH = document.getElementById(YomediaInpagePosterTagH_{!! $data['zid'] !!});
    var posterW = document.getElementById(YomediaInpagePosterTagW_{!! $data['zid'] !!});
    var YomediaStyleVideo_{!! $data['zid'] !!} = document.getElementById('YomediaStyleVideo_{!! $data['zid'] !!}');
    if(window.screen.height >= window.screen.width) {
        container_width_{!! $data['zid'] !!} = PublisherContainer_{!! $data['zid'] !!}.clientWidth - (PublisherContainer_{!! $data['zid'] !!}.clientWidth * 0.1);
        container_height_{!! $data['zid'] !!} = container_width_{!! $data['zid'] !!} / YomediaInpageRatio_{!! $data['zid'] !!};
    }else{
        container_width_{!! $data['zid'] !!} = PublisherContainer_{!! $data['zid'] !!}.clientWidth - (PublisherContainer_{!! $data['zid'] !!}.clientWidth * 0.345);
        container_height_{!! $data['zid'] !!} = container_width_{!! $data['zid'] !!} / YomediaInpageRatio_{!! $data['zid'] !!};
    }
    var video = playerWrapper.getElementsByTagName("video");
    for(var i =0 ;i<video.length;i++){
        video[i].style.minHeight = container_height_{!! $data['zid'] !!}+"px";
        video[i].style.maxHeight = container_height_{!! $data['zid'] !!}+"px";
    }
    var divVideo = playerWrapper.getElementsByTagName("div")[0];

    if (window.screen.height >= window.screen.width) {
        playerWrapper.style.top = '21%';
        playerWrapper.style.left = '0%';
        playerWrapper.style.right = '0%';
        posterH.style.display = 'block';
        posterW.style.display = 'none';
        playerWrapper.style.zIndex = 2;
        playerWrapper.style.width =container_width_{!! $data['zid'] !!}+'px';
        playerWrapper.style.height =container_height_{!! $data['zid'] !!}+'px';
    } else {
        playerWrapper.style.top = '8%';
        playerWrapper.style.left = 'auto';
        playerWrapper.style.right = '32%';
        playerWrapper.style.display = 'block';
        playerWrapper.style.width = '48%';
        playerWrapper.style.height = 'auto';
        posterH.style.display = 'none';
        posterW.style.display = 'block';
    }

}
function appendToMiddle(appendElement, container){
    var children        = container.childNodes;
    var calculateHeight = 0;
    var eleToInsert     = 0;
    var insertableNode  = [];
    for(var i = 0; i < children.length; i++) {
        if(children[i].clientHeight > 0){
            calculateHeight = calculateHeight+ children[i].clientHeight;
        }
        if(children[i].nodeType == 1){
            insertableNode.push(i);
        }
        if(calculateHeight >= (container.clientHeight / 2)){
            if(typeof(children[i + 1]) != 'undefined' && children[i + 1].nodeType == 1){
                eleToInsert = i + 1;
            }
            else{
                eleToInsert = insertableNode[insertableNode.length - 1];
            }
            container.insertBefore(appendElement, children[eleToInsert]);
            return true;
        }
    }
    return false;
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

    if ( (ybottom >= windowY && ybottom < endViewPort) || (elementY >= windowY && elementY < endViewPort) ) {
        return true;
    }
    else
    {
        return false;
    }
}

document.onreadystatechange = function () {
    if (document.readyState == "complete") {
		 if(!avlInteractModule.isMobile()){
            return false;
        }
        showYoMediaPopupAd_{!! $data['zid'] !!}(1);

        window.onclick = function(event) {
            var checkID = YomediaInpagePosterTagH_{!! $data['zid'] !!};
            var checkID2 = YomediaInpagePosterTagW_{!! $data['zid'] !!};
            if (checkID == event.target.id || checkID2 == event.target.id) {
                @if(!empty($thirdClickTrackArr))
                    @foreach( $thirdClickTrackArr as $item )
                        avlHelperModule.embedTracking("{!! trim(str_replace('[timestamp]', time(), $item)) !!}");
                    @endforeach
                @endif
            	if (typeof _YoClick != 'undefined' && avlHelperModule.validateUrl(_YoClick)) {
                	avlHelperModule.embedTracking(_YoClick);
                }
            }
        };


        window.onscroll = function(){
            resizeAds_{!! $data['zid'] !!}();
            var adContainer = document.getElementById(YomediaInpageAD_{!! $data['zid'] !!});
            if(adContainer != null){
                 if(seenByViewport_{!! $data['zid'] !!}(adContainer)){
                    document.getElementById('YomediaInpageContent_561').style.display = 'block';
                     //document.getElementById('YomediaInpageContent_561').style.position = 'fixed';
                 }
                 else{
                     document.getElementById('YomediaInpageContent_561').style.display = 'none';
                 }
            }
        }


    }
}
var videoWrapperYomedia;
var adContainerYomedia;
var adsManagerYomedia;
var adsLoaderYomedia;
var adDisplayContainer;
var videoContentYomedia;
var videoContainerYomedia;
var skipAfterYomedia = 3;
var currentAdYomedia = '';
var defaultVolumeYomedia =1;
var skipAbleYomedia;
var containerWidthYomedia = 0;
var containerHeightYomedia = 0;
var showadsYomedia = true;
var pauseadsYomedia = true;
var pauseadsAYomedia = false;

function init(content_video) {

    videoContentYomedia                = document.getElementById(content_video);

    videoContainerYomedia              = document.getElementById(YomediaInpagePlayer_{!! $data['zid'] !!});
    videoWrapperYomedia                = videoContainerYomedia.parentNode;
    containerWidthYomedia              = container_width_{!! $data['zid'] !!};
    containerHeightYomedia             = container_height_{!! $data['zid'] !!};
    videoContainerYomedia.style.height = containerHeightYomedia;
    videoContentYomedia.style.minHeight = container_height_{!! $data['zid'] !!} +'px';
    videoContentYomedia.style.maxHeight = container_height_{!! $data['zid'] !!}+'px';
}

function createAdDisplayContainer() {
    adContainerYomedia = document.getElementById(YomediaInpageAdContainer_{!! $data['zid'] !!});
    adDisplayContainer = new google.ima.AdDisplayContainer(adContainerYomedia);
}
function requestAds() {
    if(showadsYomedia == true) {
        showadsYomedia = false;
        createAdDisplayContainer();
        adDisplayContainer.initialize();
        adsLoaderYomedia = new google.ima.AdsLoader(adDisplayContainer);
        adsLoaderYomedia.addEventListener(
            google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED,
            onAdsManagerLoaded,
            false);
        adsLoaderYomedia.addEventListener(
            google.ima.AdErrorEvent.Type.AD_ERROR,
            onAdError,
            false);

        var adsRequest = new google.ima.AdsRequest();

        adsRequest.adTagUrl = '{!! route('makeVast', ['aid'=> $data['ad']->id,'fpid'=>$data['fpid'],'zid'=>$data['zid'],'cs'=>$data['checksum'],'ovr'=>!empty($data['ovr']) ]) !!}&ref='+encodeURIComponent(window.location.href);

        adsRequest.linearAdSlotWidth = containerWidthYomedia;
        adsRequest.linearAdSlotHeight = containerHeightYomedia;

        adsLoaderYomedia.requestAds(adsRequest);
    }
}

function onAdsManagerLoaded(adsManagerLoadedEvent) {
    // Get the ads manager.
    adsManagerYomedia = adsManagerLoadedEvent.getAdsManager(
        videoContentYomedia);  // should be set to the content video element

    // Add listeners to the required events.
    adsManagerYomedia.addEventListener(
        google.ima.AdErrorEvent.Type.AD_ERROR,
        onAdError);
    adsManagerYomedia.addEventListener(
        google.ima.AdEvent.Type.CONTENT_PAUSE_REQUESTED,
        onContentPauseRequested);
    adsManagerYomedia.addEventListener(
        google.ima.AdEvent.Type.CONTENT_RESUME_REQUESTED,
        onContentResumeRequested);
    adsManagerYomedia.addEventListener(
        google.ima.AdEvent.Type.ALL_ADS_COMPLETED,
        onAdEvent);

    // Listen to any additional events, if necessary.
    adsManagerYomedia.addEventListener(
        google.ima.AdEvent.Type.LOADED,
        onAdEvent);
    adsManagerYomedia.addEventListener(
        google.ima.AdEvent.Type.STARTED,
        onAdEvent);
    adsManagerYomedia.addEventListener(
        google.ima.AdEvent.Type.COMPLETE,
        onAdEvent);
    adsManagerYomedia.addEventListener(
        google.ima.AdEvent.Type.CLICK,
        onAdEvent);
    adsManagerYomedia.addEventListener(
        google.ima.AdEvent.Type.SKIPPED,
        onAdEvent);
    try {
        adsManagerYomedia.init(containerWidthYomedia, containerHeightYomedia, google.ima.ViewMode.NORMAL);
        adsManagerYomedia.start();
    } catch (adError) {
        onContentResumeRequested();
    }
}

function onAdEvent(adEvent) {
    currentAdYomedia = adEvent.getAd();
    switch (adEvent.type) {
        case google.ima.AdEvent.Type.LOADED:
            if (!currentAdYomedia.isLinear()) {
            }
            else{
                adsManagerYomedia.setVolume(defaultVolumeYomedia);
            }
            break;
        case google.ima.AdEvent.Type.STARTED:
            break;
        case google.ima.AdEvent.Type.COMPLETE:
            showadsYomedia = true;
            break;
        case google.ima.AdEvent.Type.CLICK :
            showAdResume();
            break;
        case google.ima.AdEvent.Type.PAUSED :
            showAdResume();
            break;
        case google.ima.AdEvent.Type.SKIPPED :
            showadsYomedia = true;
            break;

    }
}

function onAdError(adErrorEvent) {
    // Handle the error logging.
    adsManagerYomedia.destroy();
    onContentResumeRequested();
}

function setupUIForAds(){
    //TODO render mute button
}

function setupUIForContent(){
    document.getElementById('btnResumeAd') && document.getElementById('btnResumeAd').remove();
}

function showAdResume(){
    if(pauseadsYomedia == true){
        adsManagerYomedia.pause();
        if(pauseadsAYomedia == false){
            var resumeBtn = document.createElement('a');
            resumeBtn.setAttribute('id', 'btnResumeAd');
            resumeBtn.setAttribute('style', 'margin: -30px 0 0 -30px;display: block;padding: 30px; position:absolute;top:32%;left:50%;z-index: 999;background: url(http://static.yomedia.vn/demo/play.png);background-size : 100% 100%;z-index: 9999;cursor: pointer');
            if(window.screen.height < window.screen.width){
                resumeBtn.style.left = 'auto';
                resumeBtn.style.top = '30%';
                resumeBtn.style.right = '45%';
            }else{
                resumeBtn.style.left = '50%';
                resumeBtn.style.right = '50%';
            }
            resumeBtn.addEventListener("click", resumeAd);
            videoWrapperYomedia.appendChild(resumeBtn);
        }else{
            document.getElementById('btnResumeAd').style.display="block";
        }
        pauseadsYomedia = false;
    }

}

function resumeAd(){
    adsManagerYomedia.resume();
    document.getElementById('btnResumeAd').style.display='none';
    pauseadsAYomedia = true;
    pauseadsYomedia = true;
}

function adMute(){
    adsManagerYomedia.setVolume(0);
}

function adUnmute(){
    adsManagerYomedia.setVolume(defaultVolumeYomedia);
}

function updateCountdown(){
}

function onContentPauseRequested() {
    setupUIForAds();
}

function onContentResumeRequested() {
    setupUIForContent();
    initVideoPlayer();
}

function initVideoPlayer() {
    adContainerYomedia.innerHTML = '';

}
@include("footer")


