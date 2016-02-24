<?php
if(!isset($_GET['v']) || $_GET['v'] != 3){
    $ad->vast_version = 2;
}else{
    $ad->vast_version = 3;
}
?>
@if($ad->vast_version == 2)
<VAST version="2.0">
@elseif($ad->vast_version == 3)
<VAST xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="3.0" xsi:noNamespaceSchemaLocation="vast3_draft.xsd">
@endif
    <Ad id="{!! $ad->id !!}">
            <InLine>
                <AdSystem version="2.0">{!! Config::get('vast.adSystem') !!}</AdSystem>
                <AdTitle>{!! $ad->title !!}</AdTitle>
                <Description/>
                <Survey/>
                <Error></Error>
                {!! $ad->impTracksTag !!}
                <Creatives>
                    <Creative sequence="1" AdID="">
                        @if($ad->linear == 'linear')
                            <Linear @if ($ad->skipads)skipoffset="00:00:{!! sprintf('%02d', $ad->skipads)  !!}"@endif>
                                {!! $ad->linearTracks !!}
                                <Duration>{!! $ad->durationText !!}</Duration>
                                <VideoClicks>
                                    <ClickThrough><![CDATA[{!!  $ad->trackClick  !!}]]></ClickThrough>
                                    @if($ad->third_click_track)
                                        {!!  $ad->trackClick3rd  !!}
                                    @endif
                                </VideoClicks>
                                <MediaFiles>
                                    <MediaFile bitrate="{!! $ad->bitrate !!}" delivery="progressive" height="{!! $ad->height !!}" maintainAspectRatio="true" scalable="true" type="{!! $ad->creativeType !!}" width="{!! $ad->width !!}" minSuggestedDuration="{!! $ad->durationText !!}" @if(!empty($ad->vpaid)) apiFramework="VPAID" @endif><![CDATA[{!! $ad->file !!}]]></MediaFile>
                                </MediaFiles>
                            </Linear>
                        @else
                            <NonLinearAds skipoffset="00:00:{!!  trim($ad->skipads)  !!}">
                                {!! $ad->nonLinearTracks !!}
                                <NonLinear height="{!! $ad->height !!}" width="{!! $ad->width !!}" minSuggestedDuration="{!! $ad->durationText !!}">
                                    <StaticResource creativeType="{!! $ad->creativeType !!}"><![CDATA[{!! $ad->file !!}]]></StaticResource>
                                    <NonLinearClickThrough><![CDATA[{!! $ad->trackClick !!}]]></NonLinearClickThrough>
                                </NonLinear>
                            </NonLinearAds>
                        @endif
                    </Creative>
                </Creatives>
            </InLine>
        </Ad>
</VAST>
