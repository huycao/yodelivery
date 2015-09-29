<?php 
    $category_name = $data['category_name'];
    $publisher_domain = $data['publisher_domain'];
    $ad_format = $data['ad_format'];
    $rd = $data['rd'];
?>
// GA  Analytics
avlHelperModule.embedTrackingIframe("GA","http://static.yomedia.vn/analytics.html?utm_campaign=Yomedia&utm_source={!! $category_name !!}&utm_medium={!! $publisher_domain !!}&utm_content={!! $ad_format !!}&rd={!! $rd !!}");
