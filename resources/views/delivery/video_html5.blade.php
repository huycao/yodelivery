avlHelperModule.embedCss('http://static.yomedia.vn/public/styles/ads-label.css');
avlHelperModule.embedCss('http://static.yomedia.vn/public/styles/black-poster.css');
avlHelperModule.embedCss('http://static.yomedia.vn/public/styles/videojs.vast.css');
avlHelperModule.embedCss('http://static.yomedia.vn/public/styles/video-js.css');
avlHelperModule.embedCss('http://static.yomedia.vn/public/styles/videojs.vpaid.css');

avlHelperModule.embedJs('http://static.yomedia.vn/public/js/videojs/video.js');
avlHelperModule.embedJs('http://static.yomedia.vn/public/js/videojs/es5-shim.js');
avlHelperModule.embedJs('http://static.yomedia.vn/public/js/videojs/ie8fix.js');
avlHelperModule.embedJs('http://static.yomedia.vn/public/js/videojs/swfobject.js');
avlHelperModule.embedJs('http://static.yomedia.vn/public/js/videojs/videojs-vast-vpaid.js');

var autoStart{!! $data['zid'] !!} = true;
var playerId{!! $data['zid'] !!} = "{!! $data['eid'] or '' !!}";
var zone{!! $data['zid'] !!} = {!! $data['zid'] !!};
var a{!! $data['zid'] !!} = {!! $data['aid'] !!};
var fp{!! $data['zid'] !!} = {!! $data['fpid'] !!};
var timeRemove{!! $data['zid'] !!} = 30
var el{!! $data['zid'] !!} = 'AdvalueVideoPreroll-{!! $data['zid'] !!}-{!! $data['r'] !!}'
var elWidth{!! $data['zid'] !!} = {!! $data['ew'] or ''  !!};
var elHeight{!! $data['zid'] !!} = {!! $data['eh'] or ''  !!};
var InlineVideo{!! $data['zid'] !!} = new Array();

function prerollComplete() {
    setTimeout("avlInteractModule.removeVideoInline(playerId{!! $data['zid'] !!}, el{!! $data['zid'] !!})", 1);
}

function onAdSchedulingComplete(ads) {
    if (!ads.length ) {
	    prerollComplete();
    }
}

function onVPAIDAdComplete() {
    prerollComplete();
}

function onLinearAdFinish() {
    prerollComplete();
}

function onLinearAdSkipped() {
    prerollComplete();
}

function loadAds{!! $data['zid'] !!}() {
	if ('' != playerId{!! $data['zid'] !!}) {
		var domPlayerInner = domManipulate.getElid(playerId{!! $data['zid'] !!});
		if (domPlayerInner) {
        	var domWrapPlayer = domManipulate.create('div', el{!! $data['zid'] !!}, 'position:relative;top:0;bottom:0;');
        
        	if(playerId{!! $data['zid'] !!} ===''){
        		pid = "YoMediaDiv"+el{!! $data['zid'] !!};
        		document.body.innerHTML += '<div id="'+playerId{!! $data['zid'] !!}+'"></div>';
        	}
        
        	
        	
        	var domPlayerAds = domManipulate.create('div', 'inner' + el{!! $data['zid'] !!}, 'position:relative;width:' + elWidth{!! $data['zid'] !!} + 'px;height:' + elHeight{!! $data['zid'] !!} + 'px;', '<div id="' + avlConfig.get('ICW') + el{!! $data['zid'] !!} + '"><video id="yomedia-video-{!! $data['zid'] !!}" class="video-js vjs-default-skin" width="'+elWidth{!! $data['zid'] !!}+'" height="'+elHeight{!! $data['zid'] !!}+'" src="{!! $data['ad']->source_url !!}"></video></div>');
            domManipulate.append(domWrapPlayer, domPlayerInner);
            domWrapPlayer.appendChild(domPlayerAds);
            domWrapPlayer.appendChild(domPlayerInner);
            var styleDomPlayerInner = domPlayerInner.getAttribute('style');
            if (styleDomPlayerInner == null) styleDomPlayerInner = '';
            styleDomPlayerInner = 'display: none;' + styleDomPlayerInner;
            domPlayerInner.setAttribute('style', styleDomPlayerInner);
        }
    }
}

loadAds{!! $data['zid'] !!}();
document.onreadystatechange = function () {
    if (document.readyState == "complete") {
    	if (domManipulate.getElid("yomedia-video-{!! $data['zid'] !!}")) {
        	vjs_yomedia_{!! $data['zid'] !!}=videojs("yomedia-video-{!! $data['zid'] !!}", {hls: {withCredentials: false},"controls": false,"autoplay": false, "preload": "false" });							
    		vjs_yomedia_{!! $data['zid'] !!}.vastClient({
       			url: "{!! AD_SERVER_FILE !!}/vast?ec=0&wid={!! $data['wid'] !!}&zid={!! $data['zid'] !!}&fpid={!! $data['fpid'] !!}",
           		playAdAlways: true 
       		});
     		vjs_yomedia_{!! $data['zid'] !!}.play();
     		 vjs_yomedia_{!! $data['zid'] !!}.on('ended', function(evt) {
               onLinearAdFinish();
           });
       }
    }
}