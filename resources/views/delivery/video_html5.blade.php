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

function prerollCompleteYomedia() {
    setTimeout("avlInteractModule.removeVideoInline(playerId{!! $data['zid'] !!}, el{!! $data['zid'] !!})", 500);
}

function onAdSchedulingCompleteYomedia(ads) {
    if (!ads.length ) {
	    prerollCompleteYomedia();
    }
}

function onVPAIDAdCompleteYomedia() {
    prerollCompleteYomedia();
}

function onLinearAdFinishYomedia() {
    prerollCompleteYomedia();
}

function onLinearAdSkippedYomedia() {
    prerollCompleteYomedia();
}

function loadAds{!! $data['zid'] !!}() {	
	if ('' != playerId{!! $data['zid'] !!}) {
		if (!document.getElementById(playerId{!! $data['zid'] !!})){
			if (document.getElementsByClassName(playerId{!! $data['zid'] !!})[0]) {
				document.getElementsByClassName(playerId{!! $data['zid'] !!})[0].setAttribute('id', playerId{!! $data['zid'] !!});
			}
		}
		
		var domPlayerInner = domManipulate.getElid(playerId{!! $data['zid'] !!});
		if (domPlayerInner) {
			if(avlInteractModule.isMobile() == true){
				elWidth{!! $data['zid'] !!} = domPlayerInner.clientWidth;
				elHeight{!! $data['zid'] !!} = domPlayerInner.clientHeight;
				var plf = 'mobile';
			} else {
				var plf = 'pc';
			}
        	var domWrapPlayer = domManipulate.create('div', el{!! $data['zid'] !!}, 'position:relative;top:0;bottom:0;');
        
        	if(playerId{!! $data['zid'] !!} ===''){
        		pid = "YoMediaDiv"+el{!! $data['zid'] !!};
        		document.body.innerHTML += '<div id="'+playerId{!! $data['zid'] !!}+'"></div>';
        	}      
        	<?php 
        	    $ovr = 0;
                if (!empty($data['ovr'])) {
                    $ovr = 1;
                }
                $source_2 = '';
                if (!empty($data['ad']->source_url2)) {
                    $source_2 = urlencode($data['ad']->source_url2);
                }
        	?>
        	var domPlayerAds = domManipulate.create('div', 'inner' + el{!! $data['zid'] !!}, 'position:relative;width:' + elWidth{!! $data['zid'] !!} + 'px;height:' + elHeight{!! $data['zid'] !!} + 'px;', '<iframe id="' + avlConfig.get('ICW') + el{!! $data['zid'] !!} + '" name="perrier" src="{!! STATIC_URL !!}public/source/videojs/ova_html.html?aid={!! $data['aid'] !!}&fpid={!! $data['fpid'] !!}&zid={!! $data['zid'] !!}&rt=1&cs={!! $data['checksum'] !!}&ovr={!! $ovr !!}&ref={!! $data['ref'] !!}&yw='+elWidth{!! $data['zid'] !!}+'&yh='+elHeight{!! $data['zid'] !!}+'&poster={!! $source_2 !!}&plf='+plf+'&ord={!! time() !!}" style="display:block;overflow:hidden;z-index:1000!important;border:0;position:relative;width:100%;height:100%;"></iframe>');
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


function addAnEventListener_{!! $data['zid'] !!}(obj,evt,func){
    if ('addEventListener' in obj){
        obj.addEventListener(evt,func, false);
    } else if ('attachEvent' in obj){//IE
        obj.attachEvent('on'+evt,func);
    }
}

function iFrameListener_{!! $data['zid'] !!}(event){
     fn_{!! $data['zid'] !!} = event.data;
     if ('string' == typeof fn_{!! $data['zid'] !!} &&  fn_{!! $data['zid'] !!}.toLowerCase().indexOf("yomedia") >= 0) {
    	 eval(fn_{!! $data['zid'] !!});
     }
}

var fn_{!! $data['zid'] !!}='';
addAnEventListener_{!! $data['zid'] !!}(window,'message',iFrameListener_{!! $data['zid'] !!});