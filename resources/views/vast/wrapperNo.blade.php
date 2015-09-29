<VAST version="2.0">
    <Ad id="{{$ad->id}}">
        <Wrapper>
            <AdSystem>{!! Config::get('vast.adSystem') !! }</AdSystem>
            {!! $ad->impTracksTag !!}
            <VASTAdTagURI><![CDATA[{!! $ad->wrapper_tag !!}]]></VASTAdTagURI>
        </Wrapper>
    </Ad>
</VAST>