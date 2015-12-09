<?php
use App\Models\RedisBaseModel;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


$app->get('/', [
    'as' => 'HomePage', 'uses' => 'App\Http\Controllers\HomeController@index'
]);

$app->get('/videos', [
    'as' => 'VideoPage', 'uses' => 'App\Http\Controllers\HomeController@video'
]);

$app->get('/publisher', [
    'as' => 'PublicsherPage', 'uses' => 'App\Http\Controllers\HomeController@publisher'
]);

$app->get('/advertisers', [
    'as' => 'AdvertisersPage', 'uses' => 'App\Http\Controllers\HomeController@advertiser'
]);

$app->get('/about-us', [
    'as' => 'AboutUsPage', 'uses' => 'App\Http\Controllers\HomeController@aboutUs'
]);

$app->get('/contact-us', [
    'as' => 'ContactUsPage', 'uses' => 'App\Http\Controllers\HomeController@contactUs'
]);

$app->get('/contact-info', [
    'as' => 'ContactInfoPage', 'uses' => 'App\Http\Controllers\HomeController@contactInfo'
]);

$app->post('/contact-info', [
    'as' => 'ContactInfoPage', 'uses' => 'App\Http\Controllers\HomeController@contactInfo'
]);

$app->group(['prefix' => 'demo'], function($app)
{
    $app->get('tvc', 'App\Http\Controllers\HomeController@demoTVC');
    $app->get('run-vast-support', 'App\Http\Controllers\HomeController@demoVast');
    $app->get('run-popup', 'App\Http\Controllers\HomeController@demoPopup');
    $app->get('balloon', 'App\Http\Controllers\HomeController@demoBalloon');
    $app->get('pause-vast', 'App\Http\Controllers\HomeController@demoPauseVast');
    $app->get('sidekicknew', 'App\Http\Controllers\HomeController@demoSidekick');
});

$app->get('delivery', 'App\Http\Controllers\DeliveryController@adsProcess');
$app->get('delivery/ova', 'App\Http\Controllers\DeliveryController@makeOva');
$app->get('make-vast', [
    'as'    =>  'makeVast',
    'uses'   =>  'App\Http\Controllers\DeliveryController@makeVast'
]);
$app->get('track', 'App\Http\Controllers\DeliveryController@trackEvent');
$app->get('vast', 'App\Http\Controllers\DeliveryController@adsProcess');
$app->get('rt', 'App\Http\Controllers\DeliveryController@retargeting');
$app->get('test-sentinel', 'App\Http\Controllers\DeliveryController@testSentinel');
$app->get('render-vast', 'App\Http\Controllers\DeliveryController@renderVast');
$app->get('get-vast-tag', 'App\Http\Controllers\DeliveryController@getVastTag');

$app->get('conversion', 'App\Http\Controllers\ConversionController@trackingConversion');
$app->get('mapp', 'App\Http\Controllers\DeliveryController@getApiAd');
// use Jenssegers\Mongodb\Model as Moloquent;
// class RT extends Moloquent{
//     protected $table = 'trackings_2015_07';
//     protected $connection = 'mongodb';
// }
// 


$app->get('abc', function(){
    $parameters = ["tcp://" . env('REDIS_HOST_CLUSTER', '127.0.0.1') ];
    $options    = ['cluster' => 'redis'];
    $connection = new Predis\Client($parameters, $options);
   
    pr($connection->zrange("LatestRequest_104_" . getClientIp(), 0, -1),1);

});