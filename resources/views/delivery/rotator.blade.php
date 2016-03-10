@include("url_track_ga")
@if( count($listAlternateAd) > 0 )

<?php
	
	$YoMediaZone3rd = "YoMediaZone3rd".$zid;
	$YoMediaWeight3rd = "YoMediaWeight3rd".$zid;
	$YoMediaCookie3rd = "YoMediaCookie3rd".$zid;

?>

var {!! $YoMediaZone3rd !!} = new Array();
var {!! $YoMediaWeight3rd !!} = new Array();



@foreach( $listAlternateAd as $key=>$item)
{!! $YoMediaZone3rd !!}[{!! $key !!}] = '{!! strToHex(str_replace("\r\n", '\n', $item->code)) !!}';
{!! $YoMediaWeight3rd !!}[{!! $key !!}] = '{!! $item->weight !!}';
@endforeach

avlInteractModule.rotatorPercentAd('{!! $YoMediaCookie3rd !!}', {!! $YoMediaZone3rd !!}, {!! $YoMediaWeight3rd !!}, '');

@endif
