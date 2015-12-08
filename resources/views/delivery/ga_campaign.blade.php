<?php 
    $publisher_domain = urlencode($data['publisher_domain']);
    $ad_format = urlencode($data['ad_format']);
    $banner = urlencode(str_replace(' ', '_', $data['ad']->name));
    $flight = urlencode($data['flight_name']);
    $effect = isset($data['effect']) ? $data['effect'] : '';
    $rd = $data['rd'];    
?>

avlHelperModule.embedTrackingIframe("GA","http://static.yomedia.vn/campaigns.html?utm_medium={!! $ad_format !!}&utm_content={!! $banner !!}&utm_campaign={!! $flight !!}&utm_term={!! $effect !!}&utm_source={!! $publisher_domain !!}&rd={!! $rd !!}");
avlHelperModule.embedTrackingIframe("YO_AI","{!! AD_SERVER_FILE !!}analytics/index.php?count=ad_impression&rd={!! $rd !!}");