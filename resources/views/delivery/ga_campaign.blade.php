<?php 
    $publisher_domain = $data['publisher_domain'];
    $ad_format = $data['ad_format'];
    $banner = str_replace(' ', '_', $data['ad']->name);
    $flight = $data['flight_name'];
    $effect = isset($data['effect']) ? $data['effect'] : '';
    $rd = $data['rd'];    
?>

avlHelperModule.embedTrackingIframe("GA","http://static.yomedia.vn/campaigns.html?utm_medium={!! $ad_format !!}&utm_content={!! $banner !!}&utm_campaign={!! $flight !!}&utm_term={!! $effect !!}&utm_source={!! $publisher_domain !!}&rd={!! $rd !!}");