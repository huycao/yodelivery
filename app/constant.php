<?php

define('UPLOAD_FILE_PATH', 'public/upload/');
define('PUBLISHER_TRAFFIC_REPORT_FILE_PATH', UPLOAD_FILE_PATH.'publisher_traffic_report/');

define('TRACKER_URL', 'http://tracking.yomedia.vn/');

define('AD_SERVER_FILE', 'http://delivery.yomedia.vn/');
define('LINK_VAST', AD_SERVER_FILE.'vast');
define('LINK_JW', AD_SERVER_FILE.'public/source/js/jwplayer5/jwp5.js');
define('LINK_AVL', AD_SERVER_FILE.'public/source/advalue.js');

//cache time 
define('CACHE_1D', 86400);
define('CACHE_2D', CACHE_1D * 2);
define('CACHE_7D', CACHE_1D * 7);
define('CACHE_1Y', CACHE_1D * 365);
define('CACHE_1M', 60);
define('CACHE_5M', 300);
define('CACHE_2H', 7200);
define('KEY_TRACKING_ZONE_TOTAL_INVENTORY', 'Tracking:total_zone_inventory:');
define('KEY_TRACKING_TOTAL_INVENTORY', 'Tracking:total_inventory:');
//STATIC URL
define('STATIC_URL', 'http://static.yomedia.vn/');
define('DEBUG_ERROR', 'DebugError');
define('DEBUG_CONTENT', 'DebugContent');
define('SHOW_BANNER', 'ShowBanner');
define('DOMAIN_COOKIE', '.yomedia.vn');