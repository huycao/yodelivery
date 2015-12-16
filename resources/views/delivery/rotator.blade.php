@include("url_track_ga")
<?php if( count($listAlternateAd) > 0 ){ ?>

<?php
	
	$YoMediaZone3rd = "YoMediaZone3rd".$zid;
	$YoMediaWeight3rd = "YoMediaWeight3rd".$zid;
	$YoMediaCookie3rd = "YoMediaCookie3rd".$zid;

?>

var {!! $YoMediaZone3rd !!} = new Array();
var {!! $YoMediaWeight3rd !!} = new Array();

<?php $index = 0 ?>

<?php foreach( $listAlternateAd as $item ){ ?>
{!! $YoMediaZone3rd !!}[{!! $index !!}] = '{!! strToHex(str_replace("\r\n", '\n', $item->code)) !!}';
{!! $YoMediaWeight3rd !!}[{!! $index !!}] = '{!! $item->weight !!}';
<?php } ?>

avlInteractModule.rotatorPercentAd('{!! $YoMediaCookie3rd !!}', {!! $YoMediaZone3rd !!}, {!! $YoMediaWeight3rd !!}, '');

<?php } ?>
