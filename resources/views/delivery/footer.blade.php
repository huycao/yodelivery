<?php 
    $category_name = urlencode($data['category_name']);
    $publisher_domain = urlencode($data['publisher_domain']);
    $ad_format = urlencode($data['ad_format']);
    $rd = $data['rd'];
?>
// GA  Analytics
avlHelperModule.embedTrackingIframe("GA","http://static.yomedia.vn/analytics.html?utm_campaign=Yomedia&utm_source={!! $category_name !!}&utm_medium={!! $publisher_domain !!}&utm_content={!! $ad_format !!}&rd={!! $rd !!}");
avlHelperModule.embedTrackingIframe("YO_GA","{!! AD_SERVER_FILE !!}analytics/index.php?count=ad_request&rd={!! $rd !!}");