var autoStart{!! $data['zid'] !!} = true;
var zone{!! $data['zid'] !!} = {!! $data['zid'] !!};
var a{!! $data['zid'] !!} = {!! $data['aid'] !!};
var fp{!! $data['zid'] !!} = {!! $data['fpid'] !!};
var timeRemove{!! $data['zid'] !!} = 30
var InlineVideo{!! $data['zid'] !!} = new Array();
var ovaPlay = 0;

avlHelperModule.loadAvlStyle();

function popupComplete() {
    setTimeout("avlInteractModule.removeOverlay()", 3000);
    setTimeout(function(){
		if (typeof tmpjwplayer != "undefined") {
			jwplayer = tmpjwplayer;
			delete window.tmpjwplayer; 
		}
    }, 1000);   
}

function onLinearAdStart() {
    ovaPlay = 1;
}

function checkPlay() {
    if (ovaPlay == 0) popupComplete();
}

function onVPAIDAdComplete() {
    popupComplete();
}

function onLinearAdFinish() {
    popupComplete();
}

function onLinearAdSkipped() {
    popupComplete();
}

function loadAds() {

	avlInteractModule.initOverlay();
	avlInteractModule.initVideoPopup(
		'', 
		800, 
		600,
		'', 
		{!! $data['wid'] !!}, 
		{!! $data['zid'] !!}, 
		fp{!! $data['zid'] !!}, 
		a{!! $data['zid'] !!}, 
		zone{!! $data['zid'] !!}, 
		autoStart{!! $data['zid'] !!}, 
		100, 
		0,
		'{!! $data['checksum'] !!}',
		{!! intval($data['ovr']) !!}
	);
	avlInteractModule.showOverlay('center', '800', '600');
	<?php $data['effect'] = 'Popup';?>
    @include('ga_campaign')
	setTimeout("checkPlay();", 30000);
}

loadAds();
@include("footer")
