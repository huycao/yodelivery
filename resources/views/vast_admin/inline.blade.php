<VAST version="2.0">
    <Ad id="{!! $ad->id !!}">
        <InLine>
            <AdSystem>{!! Config::get('vast.adSystem') !!}</AdSystem>
            <AdTitle>{!! $ad->title !!}</AdTitle>
            {!! $ad->impTracksTag !!}
            <Creatives>
			    <Creative>
			    	@if($ad->linear == 'linear')
					<Linear skipoffset="00:00:10">
			            {!! $ad->linearTracks !!}
			            <Duration>{!! $ad->durationText !!}</Duration>
			            <VideoClicks>
			                <ClickThrough><![CDATA[{!!  $ad->trackClick  !!}]]></ClickThrough>
			                @if($ad->click_tracks)
			                {!!  $ad->trackClick3rd  !!}
			                @endif
			            </VideoClicks>
			            <MediaFiles>
			                <MediaFile bitrate="{!! $ad->bitrate !!}" delivery="progressive" height="{!! $ad->height !!}" maintainAspectRatio="true" scalable="true" type="{!! $ad->creativeType !!}" width="{!! $ad->width !!}" minSuggestedDuration="{!! $ad->durationText !!}"><![CDATA[{!! $ad->file !!}]]></MediaFile>
			            </MediaFiles>
			        </Linear>
			    	@else
					<NonLinearAds>
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