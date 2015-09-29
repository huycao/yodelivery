<?php
if($ad->skipads == "" || $ad->skipads == 0){
    $ad->skipads = $ad->duration;
}
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
        <Wrapper>
            <AdSystem>{!! Config::get('vast.adSystem') !!}</AdSystem>
            {!! $ad->impTracksTag !!}
            <VASTAdTagURI><![CDATA[{!! $ad->wrapper_tag !!}]]></VASTAdTagURI>
            <Creatives>
                <Creative id="{!! $ad->id !!}">
                    @if($ad->linear == 'linear')
                    <Linear @if ($ad->skipads)skipoffset="00:00:{!!  trim($ad->skipads)  !!}"@endif>
                        {!! $ad->linearTracks !!}
                        <VideoClicks>
                            <ClickTracking id="tracking_click"><![CDATA[{!! $ad->trackClick !!}]]></ClickTracking>
                            @if($ad->third_click_track)			                	
			                	{!!  $ad->trackClick3rd  !!}
			                @endif
                        </VideoClicks>
                    </Linear>    
                    @else
                    <NonLinearAds skipoffset="00:00:{!!  trim($ad->skipads)  !!}">
                        {!! $ad->nonLinearTracks !!}
                    	<NonLinear height="{!! $ad->height !!}" width="{!! $ad->width !!}" maintainAspectRatio="true" scalable="true" minSuggestedDuration="{!! $ad->durationText !!}">
    						<StaticResource creativeType="{!! $ad->creativeType !!}"><![CDATA[{!! $ad->file !!}]]></StaticResource>
                            <NonLinearClickThrough><![CDATA[{!! $ad->trackClick !!}]]></NonLinearClickThrough>
                        </NonLinear>
                    </NonLinearAds>
                    @endif
                </Creative> 
                <Creative id="{!! $ad->id !!}">
                    <CompanionAds></CompanionAds>
                </Creative>
            </Creatives>
        </Wrapper>
    </Ad>
</VAST>