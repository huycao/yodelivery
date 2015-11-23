<?php
header('Content-Type: image/gif');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

define('REDIS_HOST', '192.168.100.17');
define('REDIS_PORT_6', 6395);

define('EXPIRE_TIME', 15);

$countList = array('ad_request', 'ad_impression', 'ad_click');

if (isset($_REQUEST['count'])) {
	$key = $_REQUEST['count'];
	if (in_array($key, $countList)) {
	  	$redis = new Redis();
	  	$redis->connect(REDIS_HOST, REDIS_PORT_6);
	  	$counter = $redis->get($key);

	  	$redis->incr($key);
	}
}

echo base64_decode("R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==");
