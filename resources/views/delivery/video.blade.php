@include("url_track_ga")
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
    setTimeout("avlInteractModule.removeVideoInline(playerId{!! $data['zid'] !!}, el{!! $data['zid'] !!})", 2000);
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

function loadAds() {
	avlInteractModule.initVideoInline(
		playerId{!! $data['zid'] !!}, 
		elWidth{!! $data['zid'] !!}, 
		elHeight{!! $data['zid'] !!},
		el{!! $data['zid'] !!}, 
		{!! $data['wid'] !!}, 
		{!! $data['zid'] !!}, 
		fp{!! $data['zid'] !!}, 
		a{!! $data['zid'] !!},
		zone{!! $data['zid'] !!},
		autoStart{!! $data['zid'] !!}, 
		10, 
		0
	);
}

loadAds();

