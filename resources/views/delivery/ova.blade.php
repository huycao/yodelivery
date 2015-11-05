<config>
<ova.title>A video ad with a show stream </ova.title>
<ova.json>
{
    "player": {
        "modes": {
            "linear": {
                "controls": {
                    "visible": false
                }
            }
        }
    },
    "ads": {
        "pauseOnClickThrough": false,
        "skipAd": {
            "enabled": "true",
            @if(!empty($ad->source_url2))
            "image": "{!! $ad->source_url2 !!}",
            "width": {!! $ad->width_2 !!},
            "height": {!! $ad->height_2 !!},
            @endif
            "showAfterSeconds": @if(!empty($ad->skipads)){!! $ad->skipads !!} @else 5 @endif
        },
        "hideLogoOnLinearPlayback": true,
        "schedule": [
	        {
                "position": "pre-roll", 
                "notice": {"show": "true","region": "AdvalueNotice","message": "<?php echo htmlentities("<p class='avlNotice' align='right'>Ad by Yomedia - Close in _countdown_ seconds</p>") ?>"},
                "tag": "<?php echo htmlspecialchars($vast, ENT_QUOTES | ENT_XML1); ?>"
	        }
        ]
    },
    "debug":{
        "levels" : " none "
    }
}
</ova.json>
</config>
