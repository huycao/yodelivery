@include("footer")
@include("url_track_ga")
<?php
$source          = "";
$destination_url = $data['ad']->destination_url;
$source          = $data['ad']->source_url;
$height          = $data['ad']->height;
$width           = $data['ad']->width;
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
    var YomediaInpageWrapper_{!! $data['zid'] !!}      = "YomediaInpageWrapper_{!! $data['zid'] !!} ";
    var YomediaInpageAD_{!! $data['zid'] !!}           = "YomediaInpageAd_{!! $data['zid'] !!} ";
    var YomediaInpagePlayer_{!! $data['zid'] !!}       = "YomediaInpagePlayer_{!! $data['zid'] !!} ";
    var YomediaInpageAdContainer_{!! $data['zid'] !!}  = "YomediaInpageAdContainer_{!! $data['zid'] !!} ";
    var YomediaInpagePosterTagH_{!! $data['zid'] !!}       = "YomediaInpagePosterH_{!! $data['zid'] !!} ";
    var YomediaInpagePosterTagW_{!! $data['zid'] !!}       = "YomediaInpagePosterW_{!! $data['zid'] !!} ";
    var imagelink_{!! $data['zid'] !!}       = "{!! $data['ad']->source_url2 !!}";
    var YomediaInpageRatio_{!! $data['zid'] !!}        = 640/360;
    var YomediaImageRatio_{!! $data['zid'] !!}        = 800/1422;
    var ad_{!! $data['zid'] !!}  = '';
    var padding = 10;
    var container_width_{!! $data['zid'] !!}  = 0;
    var container_height_{!! $data['zid'] !!}  = 0;
    var videoWrapper;
    var adContainer;
    var adsManager;
    var adsLoader;
    var adDisplayContainer;
    var intervalTimer;
    var playButton;
    var videoContent;
    var videoContainer;
    var skipAfter = 3;
    var currentAd = '';
    var defaultVolume =1;
    var skipAble;
    var containerWidth = 0;
    var containerHeight = 0;
    var showads = true;
    var pauseads = true;
    function showYoMediaPopupAd_{!! $data['zid'] !!}(s) {
        if(!avlInteractModule.isMobile()){
        return false;
        }
        var clickTag_{!! $data['zid'] !!} = encodeURIComponent("{!! URL::to('/') !!}/track?evt=click&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&to={!! urlencode($data['ad']->destination_url) !!}&cs={!! $data['checksum'] !!}");
        var a_{!! $data['zid'] !!}  = document.getElementById("Yomedia_Full_Banner_{!! $data['zid'] !!}"), b_{!! $data['zid'] !!}  = document.getElementById("backgroundId");
        void 0 == a_{!! $data['zid'] !!}  && (a_{!! $data['zid'] !!}  = domManipulate.create('div', 'Yomedia_Full_Banner_{!! $data['zid'] !!}', 'border: 0px none; margin: 0px; padding: 0px; text-align: left; overflow: visible; position: fixed; z-index: 100000; top: 0px; left: 0px;', ''), document.body.insertBefore(a_{!! $data['zid'] !!} , document.body.childNodes[0]));
        void 0 == b_{!! $data['zid'] !!}  && (b_{!! $data['zid'] !!}  = domManipulate.create('div', 'backgroundId_{!! $data['zid'] !!}', 'border: 0px none; margin: 0px; padding: 0px; text-align: left; overflow: visible; position: fixed; z-index: 9999; top: 0px; left: 0px;', ''),
                document.body.insertBefore(b_{!! $data['zid'] !!} , document.body.childNodes[0]));
        document.getElementById("backgroundId_{!! $data['zid'] !!}").setAttribute('onclick', 'closeYoMediaPopupAd_{!! $data['zid'] !!}()');
        document.getElementById("backgroundId_{!! $data['zid'] !!}").style.width = "100%";
        document.getElementById("backgroundId_{!! $data['zid'] !!}").style.height = "100%";
        document.getElementById("backgroundId_{!! $data['zid'] !!}").style.backgroundColor = 'rgba(0,0,0,0.8)';

        document.getElementById("Yomedia_Full_Banner_{!! $data['zid'] !!}").innerHTML = '<div id="Yomedia_first_view_banner_{!! $data['zid'] !!}" style="border: 0 none;position: absolute;"></div>';
        var e_{!! $data['zid'] !!}  = document.getElementById("Yomedia_first_view_banner_{!! $data['zid'] !!}");
document.getElementById("Yomedia_Full_Banner_{!! $data['zid'] !!}").style.width = window.screen.width+"px";
        var left = ( viewportwidth_{!! $data['zid'] !!} () - window.screen.width) / 2;

        e_{!! $data['zid'] !!} .style.top = "10px";
        e_{!! $data['zid'] !!} .style.left = "0px";
        e_{!! $data['zid'] !!} .style.right = "0px";
        e_{!! $data['zid'] !!} .style.width = "100%";
        e_{!! $data['zid'] !!} .style.height = "auto";
        container_width_{!! $data['zid'] !!}  = window.screen.height*0.7 * YomediaImageRatio_{!! $data['zid'] !!} ;
container_height_{!! $data['zid'] !!}  = container_width_{!! $data['zid'] !!}  / YomediaInpageRatio_{!! $data['zid'] !!} ;
        var videoTag = '<video style="opacity:0" id="'+YomediaInpagePlayer_{!! $data['zid'] !!} +'"controls preload="auto"'+
                'width="1" height="1">'+
                '<source src="{!! $data['ad']->source_url2 !!}" type="video/mp4" />'+
                '</video><div id="'+YomediaInpageAdContainer_{!! $data['zid'] !!} +'" style="width: '+container_width_{!! $data['zid'] !!} +'px; height:  '+container_height_{!! $data['zid'] !!} +'px; margin: 0 auto;position: absolute; top:16px; right: 0; z-index: 20; left: 0;"></div>';


        document.body.style.overflow = "hidden";
        document.getElementById("backgroundId_{!! $data['zid'] !!}").style.width = "100%";
        document.getElementById("backgroundId_{!! $data['zid'] !!}").style.height = "100%";
        document.getElementById("backgroundId_{!! $data['zid'] !!}").style.backgroundColor = 'rgba(0,0,0,0.8)';
            var postionIE_{!! $data['zid'] !!} = 'absolute';
            if(avlInteractModule.isWindowPhone()){
            postionIE_{!! $data['zid'] !!} = 'fixed';
            }
        e_{!! $data['zid'] !!}.innerHTML = '<div id="banner-close_{!! $data['zid'] !!} " onclick="closeYoMediaPopupAd_{!! $data['zid'] !!}()" style="width: 40px;height: 40px;background-image: url(http://static.yomedia.vn/common/close_button.png);position: '+ postionIE_{!! $data['zid'] !!} +';top: 0;right: 0;z-index: 50000;"></div></div>'+videoTag+'<a href="'+decodeURIComponent(clickTag_{!! $data['zid'] !!})+'" target="_blank"><img id="image_{!! $data['zid'] !!}" src="'+imagelink_{!! $data['zid'] !!}+'" style="max-height: '+window.screen.height*0.7 + 'px;min-height: '+window.screen.height*0.7 + 'px;height: '+window.screen.height*0.7 + 'px;display: block;margin: 0 auto;"/></a>';
        document.getElementById("banner-close_{!! $data['zid'] !!} ").setAttribute('onclick', 'closeYoMediaPopupAd_{!! $data['zid'] !!} ()');

        document.getElementById(YomediaInpageAdContainer_{!! $data['zid'] !!} ).style.width=container_width_{!! $data['zid'] !!} +"px";
        document.getElementById(YomediaInpageAdContainer_{!! $data['zid'] !!} ).style.maxWidth=container_width_{!! $data['zid'] !!} +"px";

       //avlInteractModule.setCookie("Yomedia_fv_{!! $data['zid'] !!} ", "1", 1);

        document.getElementById("YomediaInpageAdContainer_{!! $data['zid'] !!} ").addEventListener("click", requestAds);
        init(YomediaInpagePlayer_{!! $data['zid'] !!} );
        avlHelperModule.embedTracking("{!!  TRACKER_URL  !!}track?evt=impression&aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}{!!  !empty($data['ovr']) ? "&ovr=1" : ''  !!}");
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
        //setInterval(closeYoMediaPopupAd_{!! $data['zid'] !!} , 5000);
    }
    function closeYoMediaPopupAd_{!! $data['zid'] !!} () {
var element =  document.getElementById("backgroundId_{!! $data['zid'] !!}");
element.outerHTML  = "";
delete element;
var element =  document.getElementById("Yomedia_Full_Banner_{!! $data['zid'] !!}");
element.outerHTML  = "";
delete element;
        {{--document.getElementById("backgroundId_{!! $data['zid'] !!}").remove();--}}
        {{--document.getElementById("Yomedia_Full_Banner_{!! $data['zid'] !!}").remove();--}}
        document.body.style.overflow = 'auto';
    }
    function viewportwidth_{!! $data['zid'] !!} (){
        var viewportwidth_{!! $data['zid'] !!} ;
        var viewportheight_{!! $data['zid'] !!} ;
        if (typeof window.innerWidth != 'undefined') {
            viewportwidth_{!! $data['zid'] !!}  = window.innerWidth,
                    viewportheight_{!! $data['zid'] !!}  = window.innerHeight
        }
        else if (typeof document.documentElement != 'undefined'
                && typeof document.documentElement.clientWidth !=
                'undefined' && document.documentElement.clientWidth != 0) {
            viewportwidth_{!! $data['zid'] !!}  = document.documentElement.clientWidth,
                    viewportheight_{!! $data['zid'] !!}  = document.documentElement.clientHeight
        } else {
            viewportwidth_{!! $data['zid'] !!}  = document.getElementsByTagName('body')[0].clientWidth, viewportheight = document.getElementsByTagName('body')[0].clientHeight
        }
        return viewportwidth_{!! $data['zid'] !!} ;
    }
    function resizeAds_{!! $data['zid'] !!} () {
document.getElementById("Yomedia_Full_Banner_{!! $data['zid'] !!}").style.width = window.screen.width+"px";
        var e_{!! $data['zid'] !!}  = document.getElementById("Yomedia_first_view_banner_{!! $data['zid'] !!}");
        e_{!! $data['zid'] !!} .style.width = "100%";
        container_width_{!! $data['zid'] !!}  = window.screen.height*0.7 * YomediaImageRatio_{!! $data['zid'] !!} ;
        document.getElementById(YomediaInpageAdContainer_{!! $data['zid'] !!} ).style.width=container_width_{!! $data['zid'] !!} +"px";
        container_height_{!! $data['zid'] !!}  = container_width_{!! $data['zid'] !!}  / YomediaInpageRatio_{!! $data['zid'] !!} ;
        document.getElementById("image_{!! $data['zid'] !!}").style.height=window.screen.height*0.7+"px";
        init(YomediaInpagePlayer_{!! $data['zid'] !!} );
        var video =  document.getElementById(YomediaInpageAdContainer_{!! $data['zid'] !!} ).getElementsByTagName("div");
        video[0].style.width = container_width_{!! $data['zid'] !!} +"px";
        video[0].style.height = container_height_{!! $data['zid'] !!} +"px";
        var iframe =  document.getElementById(YomediaInpageAdContainer_{!! $data['zid'] !!} ).getElementsByTagName("iframe");
        iframe[0].style.width = container_width_{!! $data['zid'] !!} +"px";
        iframe[0].style.height = container_height_{!! $data['zid'] !!} +"px";
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



    function getOffsetY_{!! $data['zid'] !!} (obj) {
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


    function getViewPortHeight_{!! $data['zid'] !!} () {
        var de = document.documentElement;

        if(!!window.innerWidth)
        { return window.innerHeight; }
        else if( de && !isNaN(de.clientHeight) )
        { return de.clientHeight; }

        return 0;
    }


    function seenByViewport_{!! $data['zid'] !!} (obj) {
        var vpH_{!! $data['zid'] !!}            = getViewPortHeight_{!! $data['zid'] !!} (),
                windowY_{!! $data['zid'] !!}        = window.scroller().y, // Scroll Top
                elementY_{!! $data['zid'] !!}       = getOffsetY_{!! $data['zid'] !!} (obj);
        var elementHeight_{!! $data['zid'] !!}  = obj.clientHeight;
        var ybottom_{!! $data['zid'] !!}        = elementY_{!! $data['zid'] !!} +elementHeight_{!! $data['zid'] !!} ;
        var endViewPort_{!! $data['zid'] !!}  = vpH_{!! $data['zid'] !!}  + windowY_{!! $data['zid'] !!} ;
        if ( (ybottom_{!! $data['zid'] !!}  >= windowY_{!! $data['zid'] !!}  && ybottom_{!! $data['zid'] !!}  < endViewPort_{!! $data['zid'] !!} ) || (elementY_{!! $data['zid'] !!}  >= windowY_{!! $data['zid'] !!}  && elementY_{!! $data['zid'] !!}  < endViewPort_{!! $data['zid'] !!} ) ) {
            return true;
        }else{
            return false;
        }
    }

showYoMediaPopupAd_{!! $data['zid'] !!}(1);

window.onclick = function(event) {
    var checkID = 'image_{!! $data['zid'] !!}';
    if (checkID == event.target.id) {
        @include("ga_click")
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

var adContainer = document.getElementById(YomediaInpageAD_{!! $data['zid'] !!} );
if(adContainer != null){
    if(seenByViewport_{!! $data['zid'] !!} (adContainer)){
        document.getElementById('YomediaInpageContent_561').style.position = 'fixed';
    }
    else{
        document.getElementById('YomediaInpageContent_561').style.position = 'absolute';
    }
    }
}
window.onresize = function(){
    resizeAds_{!! $data['zid'] !!} ();
}



    function init(content_video) {

        videoContent                = document.getElementById(content_video);

        videoContainer              = document.getElementById(YomediaInpagePlayer_{!! $data['zid'] !!} );
        videoWrapper                = videoContainer.parentNode;
        containerWidth              = container_width_{!! $data['zid'] !!} ;
        containerHeight             = container_height_{!! $data['zid'] !!} ;
        videoContainer.style.height = containerHeight;
    }

    function createAdDisplayContainer() {
        adContainer = document.getElementById(YomediaInpageAdContainer_{!! $data['zid'] !!} );
        adDisplayContainer = new google.ima.AdDisplayContainer(adContainer);
    }

    function requestAds() {

        if(showads == true) {
            showads = false;
            // Create the ad display container.
            createAdDisplayContainer();
            // Initialize the container. Must be done via a user action on mobile devices.
            adDisplayContainer.initialize();
            // Create ads loader.
            adsLoader = new google.ima.AdsLoader(adDisplayContainer);
            // Listen and respond to ads loaded and error events.
            adsLoader.addEventListener(
                    google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED,
                    onAdsManagerLoaded,
                    false);
            adsLoader.addEventListener(
                    google.ima.AdErrorEvent.Type.AD_ERROR,
                    onAdError,
                    false);

            // Request video ads.
            var adsRequest = new google.ima.AdsRequest();


adsRequest.adTagUrl = '{!! route('makeVast', ['aid'=> $data['ad']->id,'fpid'=>$data['fpid'],'zid'=>$data['zid'],'cs'=>$data['checksum'],'ovr'=>!empty($data['ovr']) ]) !!}&ref='+encodeURIComponent(window.location.href);


            // Specify the linear and nonlinear slot sizes. This helps the SDK to
            // select the correct creative if multiple are returned.

            adsRequest.linearAdSlotWidth = containerWidth;
            adsRequest.linearAdSlotHeight = containerHeight;

            // adsRequest.nonLinearAdSlotWidth = 640;
            // adsRequest.nonLinearAdSlotHeight = 150;

            adsLoader.requestAds(adsRequest);
            // videoContent.style.display = 'none';
        }
    }

    function onAdsManagerLoaded(adsManagerLoadedEvent) {
        // Get the ads manager.
        adsManager = adsManagerLoadedEvent.getAdsManager(
                videoContent);  // should be set to the content video element

        // Add listeners to the required events.
        adsManager.addEventListener(
                google.ima.AdErrorEvent.Type.AD_ERROR,
                onAdError);
        adsManager.addEventListener(
                google.ima.AdEvent.Type.CONTENT_PAUSE_REQUESTED,
                onContentPauseRequested);
        adsManager.addEventListener(
                google.ima.AdEvent.Type.CONTENT_RESUME_REQUESTED,
                onContentResumeRequested);
        adsManager.addEventListener(
                google.ima.AdEvent.Type.ALL_ADS_COMPLETED,
                onAdEvent);

        // Listen to any additional events, if necessary.
        adsManager.addEventListener(
                google.ima.AdEvent.Type.LOADED,
                onAdEvent);
        adsManager.addEventListener(
                google.ima.AdEvent.Type.STARTED,
                onAdEvent);
        adsManager.addEventListener(
                google.ima.AdEvent.Type.COMPLETE,
                onAdEvent);
        adsManager.addEventListener(
                google.ima.AdEvent.Type.CLICK,
                onAdEvent);
        adsManager.addEventListener(
                google.ima.AdEvent.Type.SKIPPED,
                onAdEvent);
        try {
            // Initialize the ads manager. Ad rules playlist will start at this time.
            adsManager.init(containerWidth, containerHeight, google.ima.ViewMode.NORMAL);
            // Call play to start showing the ad. Single video and overlay ads will
            // start at this time; the call will be ignored for ad rules.
            adsManager.start();
        } catch (adError) {
            // An error may be thrown if there was a problem with the VAST response.
            onContentResumeRequested();
        }
    }

    function onAdEvent(adEvent) {
        // Retrieve the ad from the event. Some events (e.g. ALL_ADS_COMPLETED)
        // don't have ad object associated.
        currentAd = adEvent.getAd();
        switch (adEvent.type) {
            case google.ima.AdEvent.Type.LOADED:
                // This is the first event sent for an ad - it is possible to
                // determine whether the ad is a video ad or an overlay.
                if (!currentAd.isLinear()) {
                    // Position AdDisplayContainer correctly for overlay.
                    // Use currentAd.width and currentAd.height.
                }
                else{
                    adsManager.setVolume(defaultVolume);
                }
                break;
            case google.ima.AdEvent.Type.STARTED:
                // This event indicates the ad has started - the video player
                // can adjust the UI, for example display a pause button and
                // remaining time.
                if (currentAd.isLinear()) {
                    // For a linear ad, a timer can be started to poll for
                    // the remaining time.
                    intervalTimer = setInterval(updateCountdown,300); // every 300ms
                }
                break;
            case google.ima.AdEvent.Type.COMPLETE:
                // This event indicates the ad has finished - the video player
                // can perform appropriate UI actions, such as removing the timer for
                // remaining time detection.

                if (currentAd.isLinear()) {
                    clearInterval(intervalTimer);
                }
                showads = true;
                break;
            case google.ima.AdEvent.Type.CLICK :
                showAdResume();
                break;
            case google.ima.AdEvent.Type.PAUSED :
                break;
            case google.ima.AdEvent.Type.SKIPPED :
                showads = true;
                break;

        }
    }

    function onAdError(adErrorEvent) {
        // Handle the error logging.
        // console.log(adErrorEvent.getError());
        adsManager.destroy();
        onContentResumeRequested();
    }

    function setupUIForAds(){

        //TODO render mute button
    }

    function setupUIForContent(){
        document.getElementById('btnResumeAd') && document.getElementById('btnResumeAd').remove();
    }

    function showAdResume(){
        if(pauseads == true){
            adsManager.pause();
            var resumeBtn = document.createElement('a');
            resumeBtn.setAttribute('id', 'btnResumeAd');
            resumeBtn.setAttribute('style', 'margin: -30px 0 0 -30px;display: block;padding: 30px; position:absolute;top:18%;left:50%;z-index: 999;background: url(http://yomedia.vn/demo/wap/inpage-tvc3/play.png);background-size : 100% 100%;z-index: 9999;cursor: pointer');
            if(window.screen.height < window.screen.width){
                resumeBtn.style.left = 'auto';
                resumeBtn.style.top = '36%';
                resumeBtn.style.right = '42%';
            }else{
                resumeBtn.style.left = '50%';
                resumeBtn.style.right = '50%';
            }
            resumeBtn.addEventListener("click", resumeAd);
            videoWrapper.appendChild(resumeBtn);
            pauseads =false;
        }

    }

    function resumeAd(){
        adsManager.resume();
        document.getElementById('btnResumeAd').remove();
        pauseads =true;
    }

    function adMute(){
        adsManager.setVolume(0);
    }

    function adUnmute(){
        adsManager.setVolume(defaultVolume);
    }


    function updateCountdown(){


    }

    function onContentPauseRequested() {
        // videoContent.pause();
        // This function is where you should setup UI for showing ads (e.g.
        // display ad timer countdown, disable seeking etc.)
        setupUIForAds();
    }

    function onContentResumeRequested() {

        // This function is where you should ensure that your UI is ready
        // to play content. It is the responsibility of the Publisher to
        // implement this function when necessary.
        setupUIForContent();
        initVideoPlayer();
    }

    function initVideoPlayer() {
        adContainer.innerHTML = '';

    }