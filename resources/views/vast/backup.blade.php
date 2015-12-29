<?php

if(!isset($_GET['v']) || $_GET['v'] != 3){
    $ad['ast_version'] = 2;
}else{
    $ad['ast_version'] = 3;
}
?>
@if($ad['ast_version'] == 2)
    <VAST version="2.0">
@elseif($ad['ast_version'] == 3)
    <VAST xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="3.0" xsi:noNamespaceSchemaLocation="vast3_draft.xsd">
@endif
    <Ad id="{!! $ad['publisher_ad_zone_id'] !!}">
        <Wrapper>
            <AdSystem>{!! Config::get('vast.adSystem') !!}</AdSystem>
            <VASTAdTagURI><![CDATA[{!! $ad['wrapper_tag'] !!}]]></VASTAdTagURI>
            <Creatives>
                <Creative id="{!! $ad['publisher_ad_zone_id'] !!}">
                </Creative>
            </Creatives>
        </Wrapper>
    </Ad>
</VAST>