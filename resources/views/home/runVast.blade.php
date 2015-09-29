<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DEMO RUN VAST</title>
    <link rel="stylesheet" href="{{URL::to('/public/css/demo/normalize.css')}}">
    <link rel="stylesheet" href="{{URL::to('/public/css/demo/bootstrap-default.min.css')}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{URL::to('/public/css/demo/font-awesome.min.css')}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{URL::to('/public/css/main.css')}}">
    <link rel="stylesheet" href="{{URL::to('/public/css/demo/demo-vast.css')}}">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script type="text/javascript" src="{{URL::to('/public/source/js/jwplayer5/jwp5.js')}}"></script>
    <script type="text/javascript" src="{{URL::to('/public/js/swfobject.js')}}"></script>
    <?php
        $assetURL = URL::to('/public') . "/";
    ?>
    <script>
    
    var asset_url = '{{URL::to('/public')}}';
    var defaultObj = {
        player : 'player',
        adTag : 'http://yomedia.vn/vast?ec=0&wid=179&zid=187',
        vastXML : '',
        mediaFile : "{{$assetURL}}video/pub-video.mp4",
        mediaImage:"{{$assetURL}}video/traintracks480.jpg"
    }
    </script>
    <style type="text/css">
    #player_container{margin-left: 700px;}
    </style>
</head>

<body class="demo_vast">
<div class="container">
    <a id="btn_play" href="javascript:;"></a>
    <h1 class="center bgtext"><span>DEMO TVC RUN VAST</span></h1>

    <div class="text-center">
        <div id="player_container">
            <script type="text/javascript">var _avlVar = _avlVar || [];
            //_avlVar.push(["6", "197", "Banner_Inpage", "", "300", "250"]);
           // document.write('<script src="http://yomedia.vn/public/source/yo-delivery.js" type="text/javascript"><\/script>');
            </script>               
                                           
                        
            <div id="player"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
$('#btn_play').click(function(){
   $('#player_container').html('<div id="player"></div>');
    jwplayer(defaultObj.player).setup({
        autoplay : true,
        "flashplayer": asset_url + "/flash/jwplayer5/player.swf",
        "playlist": [{
            "file": defaultObj.mediaFile
        }],
        "width": 960,
        "height": 480,
        "controlbar": {
            "position": "bottom"
        },
        "plugins": {
            "ova-jw": {
                "ads": {
                    "skipAd": {
                        "enabled": "true"
                    },
                    "schedule": [{
                        "position": "pre-roll",
                        "tag": defaultObj.adTag
                    }]
                },
                "debug": {
                    "levels": "fatal, config, vast_template, http_calls"
                }
            }
        }
    });
 
})


</script>
<div id="banner_demo"></div>
<div id="banner_demo2"></div>
<div id="banner_demo3"></div>
<script type="text/javascript">
var attr = {wmode:'transparent'};
// swfobject.embedSWF('{{URL::to("public/300x250_microad.swf?clickTAG=".rawurlencode("http://hayhaytv.vn"))}}', "banner_demo", "300", "250", "9.0.0","expressInstall.swf",'','',attr);
//var _avlVar = _avlVar || [];
//_avlVar.push(["6", "58", "Balloon"]);
//document.write('<sc'+'ript src="http://yomedia.vn/public/source/yo-delivery.js" type="text/javascript"></scr'+'ipt>');
</script>            
</body>
</html>