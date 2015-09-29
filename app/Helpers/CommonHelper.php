<?php

/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('pr')) {

    function pr($data, $exit = 0) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        if(strpos($user_agent, DEBUG_CONTENT) !== FALSE){
            print '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">';
            print_r($data);
            print '</pre>';
            if ($exit != 0) {
                exit();
            }
        }
    }

}

if (!function_exists('isDebugError')) {

    function isDebugError() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if(strpos($user_agent, DEBUG_ERROR) !== FALSE){
            return true;
        }
        return false;
    }

}

if (!function_exists('showBanner')) {

    function showBanner() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        if(strpos($user_agent, SHOW_BANNER) !== FALSE){
            $arrAgent = explode(' ', $user_agent);
            if (!empty($arrAgent[1]) && is_numeric($arrAgent[1]) && intval($arrAgent[1]) == $arrAgent[1] && $arrAgent[1] > 0) {
                return $arrAgent[1];
            }
        }
        return FALSE;
    }

}

if (!function_exists('isLocal')) {

    function isLocal() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if(strpos($user_agent, DEBUG_CONTENT) !== FALSE || strpos($user_agent, SHOW_BANNER) !== FALSE){
            return true;
        }
        return false;
    }

}

if (!function_exists('explode_end')) {

    function explode_end($string, $delimiter) {
        $rs = explode($delimiter, $string);
        return end($rs);
    }

}

if (!function_exists('getLastQuery')) {

    function getLastQuery() {
        $queries = DB::getQueryLog();
        return last($queries);
    }

}


if (!function_exists('array_column')) {

    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if (!isset($value[$columnKey])) {
                return false;
            }

            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!isset($value[$indexKey])) {
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }

        return $array;
    }

}

if (!function_exists('array_reindex')) {

    function array_reindex(array $input, $indexKey = null, $memberObject = false) {
        $array = array();
        if (!empty($input)) {
            foreach ($input as $value) {
                if(!$memberObject){
                    $array[$value[$indexKey]] = $value;
                }
                else{
                    $array[$value->$indexKey] = $value;
                }
            }
        }

        return $array;
    }

}


if (!function_exists('capitalSeparate')) {

    function capitalSeparate($value, $delimiter = ' ') {
        $replace = '$1' . $delimiter . '$2';
        return preg_replace('/(.)([A-Z])/', $replace, $value);
    }

}

if (!function_exists('uniqueString')) {

    function uniqueString() {
        return md5(uniqid(mt_rand()));
    }

}

function isMenuActive($routeName = '', $action = '', $activeClass = 'active', $routeParam1 = '', $routeParam2 = '') {
    if ($routeName) {
        if ($routeParam1) {
            $url = URL::to(URL::route($routeName, $routeParam1, $routeParam2));
        } else {
            $url = URL::to(URL::route($routeName));
        }

        if (!empty($action) && $action != "profile")
            $url = URL::to(URL::route($routeName)) . "/" . $action;

        if (!empty($routeParam1) && !empty($action))
            $url = URL::to(URL::route($routeName)) . "/" . $action . "/" . $routeParam1;
    }
    elseif ($action)
        $url = URL::action($action);

    if ($url == URL::current())
        return $activeClass;
    else
        return false;
}

if (!function_exists('inputChecked')) {

    function inputChecked($value, $compareValue = NULL, $isSelectBox = false) {
        if ($value == $compareValue || ($compareValue === NULL)) {
            if (!$isSelectBox)
                return "checked=checked";
            else
                return "selected=selected";
        }
        return '';
    }

}
if (!function_exists('clearCookie')) {

    function clearCookie() {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
        }
    }

}


if (!function_exists('numberVN')) {

    function numberVN($number, $separator = 0) {
        return number_format($number, $separator, '.', ',');
    }

}

if (!function_exists('img')) {

    function img($src, $w = 0, $h = 0, $zc = 1, $img_default = "img_default.jpg") {
        if (empty($src) || $src == STATIC_SERVER) {
            $src = STATIC_SERVER . "public/frontend/images/$img_default";
        }
        // if(strpos($src, STATIC_SERVER) === FALSE){
        //     $src = STATIC_SERVER . $src;
        // }
        if ($w != 0 && $h != 0) {
            return URL::to("img.php?w={$w}&h={$h}&zc={$zc}&src=" . $src);
        } elseif ($w != 0) {
            return URL::to("img.php?w={$w}&src=" . $src);
        } elseif ($h != 0) {
            return URL::to("img.php?h={$h}&src=" . $src);
        } else {
            return $src;
        }
    }

}

if (!function_exists('curlGet')) {

    function curlGet($url = '', $params = array(), $htpassInfo = '') {
        $options = array();
        $options['CURLOPT_AUTOREFERER'] = 1;
        $options['CURLOPT_CRLF'] = 1;
        $options['CURLOPT_NOPROGRESS'] = 1;
        //login htpaswd
        if ($htpassInfo) {
            $options['CURLOPT_USERPWD'] = $htpassInfo;
            $options['CURLOPT_HTTPAUTH'] = CURLAUTH_ANY;
        }

        $http = new cURL($options);
        $http->setOptions($options);
        if (substr($url, -1) != '?' && !empty($params))
            $url .= "?" . http_build_query($params);
        $src = $http->get($url);
        return $src;
    }

}

if (!function_exists('curlPost')) {

    function curlPost($link = '', $field = array()) {
        $options = array();
        $fields = array();
        $options['CURLOPT_AUTOREFERER'] = 1;
        $options['CURLOPT_CRLF'] = 1;
        $options['CURLOPT_NOPROGRESS'] = 1;
        $options['CURLOPT_RETURNTRANSFER'] = 1;
        //login htpaswd
        if ($htpassInfo) {
            $options['CURLOPT_USERPWD'] = $htpassInfo;
            $options['CURLOPT_HTTPAUTH'] = CURLAUTH_ANY;
        }

        $http = new cURL($options);
        $http->setOptions($options);
        $result = $http->post($link, $field);
        return $result;
    }

}

if (!function_exists('curl_get_file_size')) {

    function curl_get_file_size($url) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);

        $data = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        curl_close($ch);
        return $size;
    }

}

if (!function_exists('curlCopy')) {

    function curlCopy($source = '', $file = '') {
        $fh = fopen($file, 'w+');
        if ($fh) {
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $source);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FILE, $fh);
            // grab URL and pass it to the browser
            curl_exec($ch);
            // close cURL resource, and free up system resources
            curl_close($ch);
            fclose($fh);
            return true;
        }
    }

}
if (!function_exists('secsToDuration')) {

    function secsToDuration($secs) {
        $units = array(
            // "week"   => 7*24*3600,
            // "day"    =>   24*3600,
            "hour" => 3600,
            "minute" => 60,
            "second" => 1,
        );

        // specifically handle zero
        if ($secs == 0)
            return "";
        $s = "";
        foreach ($units as $name => $divisor) {
            if ($quot = intval($secs / $divisor)) {
                $text[] = str_pad($quot, 2, '00', STR_PAD_LEFT);
                $secs -= $quot * $divisor;
            } else {
                $text[] = '00';
            }
        }
        return implode(':', $text);
    }

}
if (!function_exists('generateRandomString')) {

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

}

function shareFB($url = '') {
    return "https://www.facebook.com/dialog/share?app_id=" . FB_APP_ID . "&display=popup&href=" . urlencode($url) . "&redirect_uri=" . urlencode($url);
}

if (!function_exists('formatDate')) {

    function formatDate($date, $format = 'd-M-Y') {
        return date_format($date, $format);
    }

}

if (!function_exists('trimInput')) {

    function trimInput($input = NULL) {
        if (!empty($input) && !is_array($input)) {
            return trim($input);
        } else {
            return $input;
        }
    }

}

if (!function_exists('newFolder')) {

    function newFolder($folder) {
        $arr_folder = explode('/', $folder);
        $fol = '';
        foreach ($arr_folder as $row) {
            if (!empty($row)) {
                $fol.=$row . '/';
                if (!file_exists($fol)) {
                    @mkdir($fol, 0755);
                }
            }
        }
    }

}
///get month before an number month
///ex:12 month
if (!function_exists('getMonthBefore')) {

    function getMonthBefore($num = 12) {
        $dateMonth = date('m');
        $dateYear = date('Y');

        $j = 1;
        for ($i = 1; $i <= $num; $i++) {
            if ($j > 12) {
                $dateMonth--;
                if ($dateMonth < 1) {
                    $dateMonth = 12;
                    $dateYear--;
                }
                $j = 1;
            } else {
                if ($i > 1) {
                    $dateMonth--;
                    if ($dateMonth < 1) {
                        $dateMonth = 12;
                        $dateYear--;
                    }
                    $j++;
                }
            }

            $dateTmp = $dateYear . "-" . $dateMonth;
            $dateC = strtotime($dateTmp);
            $dateFormat = date('M-Y', $dateC);
            $dateM["{$dateTmp}"] = $dateFormat;
        }
        return $dateM;
    }

}

// comment campaign
if (!function_exists('commentCampaign')) {

    function commentCampaign() {
        return [
            '1' => trans('backend::publisher/text.lowpayout'),
            '2' => trans('backend::publisher/text.misleading'),
            '3' => trans('backend::publisher/text.not_relevant'),
            '4' => trans('backend::publisher/text.offensive'),
            '5' => trans('backend::publisher/text.repetitive'),
            '6' => trans('backend::publisher/text.sexually_explicit'),
            '7' => trans('backend::publisher/text.uninteresting'),
            '-1' => trans('backend::publisher/text.orther')
        ];
    }

}

if (!function_exists('getMonthRange')) {

    function getMonthRange($number = 12) {
        $result = array();

        $stringTime = strtotime('this month');
        $dateFormat = date('Y-m-1', $stringTime);
        $dateString = date('F Y', $stringTime);
        $result[$dateFormat] = $dateString;

        for ($i = 1; $i < $number; $i++) {
            $stringTime = strtotime('next month', strtotime($dateFormat));
            $dateFormat = date('Y-m-1', $stringTime);
            $dateString = date('F Y', $stringTime);
            $result[$dateFormat] = $dateString;
        }
        return $result;
    }

}

if (!function_exists('mathECPM')) {

    //math eCPM
    function mathECPM($impression, $publisher_cost, $total_inventory) {
        if (empty($publisher_cost))
            return 0;
        return round((($publisher_cost / $total_inventory) * $impression) / 1000, 2);
    }

}

if (!function_exists('mathECPC')) {

    //math eCPC
    function mathECPC($click, $publisher_cost, $total_inventory) {
        if (empty($publisher_cost))
            return 0;
        return round(($publisher_cost / $total_inventory) * $click, 2);
    }

}

if (!function_exists('mathEarnings')) {

    //math earnings
    function mathEarnings($eCPM, $eCPC) {
        return round(($eCPM + $eCPC), 2);
    }

}

if (!function_exists('mathCRT')) {

    //math earnings
    function mathCRT($impression, $click) {
        if (empty($click))
            return 0;
        return round(($click / $impression) * 100, 2);
    }

}

if (!function_exists('calculateRatio')) {

    function calculateRatio($value, $percent) {
        return $value * ( 100 - $percent ) / 100;
    }

}

if (!function_exists('strtotimeVN')) {

    function strtotimeVN($time) {
        $dateTokens = explode('/', $time); 
        return strtotime($dateTokens[2] . '-' . $dateTokens[0] . '-' . $dateTokens[1]);    }

}

if (!function_exists('costCPM')) {
    function costCPM($impression, $cost) {
        $cpm = floor($impression/1000);
        return $cpm * $cost;
    }
}

if (!function_exists('costCPC')) {
    function costCPC($click, $cost) {
        return $click * $cost;
    }
}

if (!function_exists('getCost')) {
    function getCost($type, $impression, $click, $cost) {
        $rs = 0;
        switch ($type) {
            case 'cpm':
                $rs = costCPM($impression, $cost);
                break;
            case 'cpc':
                $rs = costCPC($click, $cost);
                break;
        }
        return $rs;
    }
}
if (!function_exists('getIP')) {
function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $_SERVER['REMOTE_ADDR'] = $ip;
    return $ip;
}
}

if (!function_exists('intvalFromTimeText')) {
function intvalFromTimeText($text = '') {
    $arrText = explode(':', $text);
    if(!empty($arrText)){
        $retval = 0;
        if(!empty($arrText[0]) && is_numeric($arrText[0])){
            $retval += 3600 * $arrText[0];
        }
        if(!empty($arrText[1]) && is_numeric($arrText[1])){
            $retval += 60 * $arrText[1];
        }
        if(!empty($arrText[2]) && is_numeric($arrText[2])){
            $retval += $arrText[2];
        }
        return $retval;
    }

    return false;
}
}

if(!function_exists('getClientIp')){
    function getClientIp(){
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif(!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return trim($ip);
    }
}

if(!function_exists('setOrSum')){
    function setOrSum($number, $value){
        return empty($number) ? $value : $number + $value;
    }
}

if(!function_exists('getWebDomain')){
    function getWebDomain($uri = ''){
        $retval = $uri;
        if(strpos($uri, 'http://') !== FALSE){
            $retval = parse_url($uri, PHP_URL_HOST);
        }
        if($retval && strpos($retval, 'www.') === 0){
            $retval = substr($retval, 4);
        }
        return $retval;
    }
}

if(!function_exists('strToHex')){
    function strToHex($string)
    {
        $pattern = array(
            '<',
            '>',
            '=',
            ' '
        );

        $string = str_split($string);
        foreach($string as &$char){
            if( in_array($char, $pattern) ){
                $char = "\x".dechex(ord($char));
            }
        }
        return implode('',$string);
    }
}

if(!function_exists('increBy')){
    function increBy(& $vari, $number = 0)
    {
        $vari = empty($vari) ? $number : $vari + $number;
        return $vari;
    }
}


if(!function_exists('isSameDomain')){
    function isSameDomain($host = '', $domain =''){
        $pos    = strpos($host, $domain);
        $diflen = (strlen($host) -strlen($domain) );
        if( ($domain == $host) || ($pos > 1 && $host[$pos - 1] == '.' && $pos == $diflen ) )
            return true;
        else
            return false;
    }
}

if ( ! function_exists('urlTracking'))
{
    function urlTracking($event = '', $adID, $flightPublisherID,$adZoneID, $checksum, $destinationUrl = '', $isOverReport = false, $referrer = ''){
        $params = array(
            'evt'   =>  $event,
            'aid'   =>  $adID,
            'fpid'  =>  $flightPublisherID,
            'zid'   =>  $adZoneID,
            'rt'    =>  App\Models\Delivery::REQUEST_TYPE_TRACKING_BEACON,
            'cs'    =>  $checksum
            );
        if($destinationUrl){
            $params['to'] = $destinationUrl;
        }
        if($isOverReport){
            $params['ovr'] = 1;
        }
        if($referrer){
            $params['ref'] = $referrer;
        }
        return URL::to("/track?") . http_build_query($params);
    }
}

if ( ! function_exists('getSiteName'))
{
    function getSiteName($domain)
    {
        $tlds = array(
                    'aero',
                    'arpa',
                    'asia',
                    'biz',
                    'cat',
                    'com',
                    'coop',
                    'edu',
                    'gov',
                    'info',
                    'jobs',
                    'mil',
                    'mobi',
                    'museum',
                    'name',
                    'net',
                    'org',
                    'post',
                    'pro',
                    'tel',
                    'travel',
        );
        $original = $domain = strtolower($domain);
        if (filter_var($domain, FILTER_VALIDATE_IP)) { return $domain; }
    
        $arr = array_slice(array_filter(explode('.', $domain, 4), function($value){
            return $value !== 'www';
        }), 0); //rebuild array indexes
    
        if (count($arr) > 2)
        {
            $count = count($arr);
            $_sub = explode('.', $count === 4 ? $arr[3] : $arr[2]);
    
            if (count($_sub) === 2) // two level TLD
            {
                $removed = array_shift($arr);
                if ($count === 4) // got a subdomain acting as a domain
                {
                    $removed = array_shift($arr);
                }
            }
            elseif (count($_sub) === 1) // one level TLD
            {
                $removed = array_shift($arr); //remove the subdomain
    
                if (strlen($_sub[0]) === 2 && $count === 3) // TLD domain must be 2 letters
                {
                    //array_unshift($arr, $removed);
                    if (in_array($arr[0], $tlds)) {
                        array_unshift($arr, $removed);
                    }
                }
                else
                {
                    if (count($arr) > 2 && in_array($_sub[0], $tlds) !== false) //special TLD don't have a country
                    {
                        array_shift($arr);
                    }
                }
            }
            else // more than 3 levels, something is wrong
            {
                for ($i = count($_sub); $i > 1; $i--)
                {
                    $removed = array_shift($arr);
                }
            }
        }
        elseif (count($arr) === 2)
        {
            $arr0 = array_shift($arr);
    
            if (strpos(join('.', $arr), '.') === false
                && in_array($arr[0], array('localhost','test','invalid')) === false) // not a reserved domain
            {
                // seems invalid domain, restore it
                array_unshift($arr, $arr0);
            }
        }
    
        return str_replace('.', '',join('', $arr));
    }
}

if (!function_exists('multipleThreadsRequest')) {
    function multipleThreadsRequest($nodes){ 
        $mh = curl_multi_init(); 
        $curl_array = array(); 
        foreach($nodes as $i => $url) 
        { 
            $curl_array[$i] = curl_init($url); 
            curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, true); 
            curl_multi_add_handle($mh, $curl_array[$i]); 
        } 
        $running = NULL; 
        do { 
            usleep(10000); 
            curl_multi_exec($mh,$running); 
        } while($running > 0); 
        
        $res = array(); 
        foreach($nodes as $i => $url) 
        { 
            $res[$url] = curl_multi_getcontent($curl_array[$i]); 
        } 
        
        foreach($nodes as $i => $url){ 
            curl_multi_remove_handle($mh, $curl_array[$i]); 
        } 
        curl_multi_close($mh); 
    } 
}