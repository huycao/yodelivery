var evt_success = 'btn-success';
var tag = '';
var typeAd = '';
var init = true;

function setUpPlayer(tag, typead, schedule) {

    var autoplay = true;
    if (validVar(tag) || validVar(typead)) {
        init = false;
    }

    if (!validVar(schedule)) {
        if (typeof(typead) == 'undefined') {
            adSchedule = {};
            autoplay = false;
        } else {
            if (typead == 'nonlinear') {
                adSchedule = {
                    "startTime": "00:00:03",
                    "duration": "recommended:10",
                    "tag": tag
                };
            } else {
                adSchedule = {
                    "position": "pre-roll",
                    "tag": tag
                };
            }
        }
    } else {
        adSchedule = schedule;
    }

    $('#player_container').html('<div id="player"></div>');
    jwplayer(defaultObj.player).setup({
        "flashplayer": asset_url + "/flash/jwplayer5/player.swf",
        "playlist": [{
            "file": defaultObj.mediaFile
        }],
        "width": 480,
        "height": 320,
        "autoplay": autoplay,
        "controlbar": {
            "position": "bottom"
        },
        "plugins": {
            "ova-jw": {
                "canFireEventAPICalls": true,
                "ads": {
                    "skipAd": {
                        "enabled": true,
                        "showAfterSeconds": 3
                    },
                    "schedule": [
                        adSchedule
                    ]
                },
                "debug": {
                    "levels": "fatal, config, vast_template, http_calls"
                }
            }
        }
    });
}

function addEventTracker(event_name, event_link) {
    var html = '<div class="input-group mt5">' +
        '<div class="input-group-addon">' + event_name + '</div>' +
        '<input type="textarea" class="form-control" placeholder="" readonly="readonly" value="' + encodeURI(event_link) + '">' +
        '</div>';
    $('#event_tracker_section').append(html);
}

function ovaPrintDebug(output) {
    try {
        console.log(output);
    } catch (error) {}
}

function onImpressionEvent(event, forced) {
    if (event != null) {
        ovaPrintDebug(event);
        $("#evt_impression").addClass(evt_success);
    }
}

function onTrackingEvent(event) {
    if (event != null) {
        ovaPrintDebug(event);
        $("#evt_" + event.eventType).addClass(evt_success);
    }
}

function onLinearAdScheduled(ad) {
    //linear or non-linear check
    $('input[type=radion][value=linear]').click();
    showAdInfo(ad);
    console.log('AD NE');
    console.log(ad);
}

function onNonLinearAdScheduled(ad) {
    $('input[type=radion][value=non-linear]').click();
    showAdInfo(ad);
}

function showAdInfo(ad) {
    $('#info_type_ad').val(ad.type);
    //list impression tracking
    if (ad.impressions.length) {
        for (var i = 0; i < ad.impressions.length; i++) {
            if (validVar(ad.impressions[i]) && validVar(ad.impressions[i].eventType) && validVar(ad.impressions[i].url)) {
                addEventTracker(ad.impressions[i].eventType, ad.impressions[i].url);
            }
        }
    }
    if (ad.linearAd) {
        //linear ad

        //event click thru
        if (validVar(ad.linearAd.clickTracking[0].id) && validVar(ad.linearAd.clickTracking[0].url)) {
            addEventTracker(ad.linearAd.clickTracking[0].id, ad.linearAd.clickTracking[0].url);
        }
        //event click thru
        if (validVar(ad.linearAd.clickThroughs[0].url)) {
            addEventTracker('click_through', ad.linearAd.clickThroughs[0].url);
        }
        //list event tracking
        if (ad.linearAd.trackingEvents.length) {
            for (var i = 0; i < ad.linearAd.trackingEvents.length; i++) {
                if (validVar(ad.linearAd.trackingEvents[i]) && validVar(ad.linearAd.trackingEvents[i].urls[0].id) && validVar(ad.linearAd.trackingEvents[i].urls[0].url)) {
                    addEventTracker(ad.linearAd.trackingEvents[i].urls[0].id, ad.linearAd.trackingEvents[i].urls[0].url);
                }
            }
        }

        //update mediaFile
        if (validVar(ad.linearAd.mediaFiles[0]) && validVar(ad.linearAd.mediaFiles[0].url)) {
            $('#info_media_file').val(ad.linearAd.mediaFiles[0].url);
        }
    } else {
        //event click thru
        if (validVar(ad.nonLinearAds[0].clickThroughs[0].url)) {
            addEventTracker('click_through', ad.nonLinearAds[0].clickThroughs[0].url);
        }
        //update mediaFile
        if (validVar(ad.nonLinearAds[0].url)) {
            $('#info_media_file').val(ad.nonLinearAds[0].url);
        }


    }
}

function validVar(var_name) {
    if (typeof(var_name) != 'undefined' && var_name) {
        return true;
    }
    return false;
}

function resetInfo(sameTag) {
    $('.event_btn .btn').removeClass(evt_success);
    if (typeof(sameTag) != 'undefined') {
        $('.ad_load_info input').val('');
        $('#event_tracker_section').html('');
    }
}

function showErrorLog(error) {
    if (!init) {
        $('#errorLog').append('<p class="text-danger">' + error + '</p>');
    }
}

function onTemplateLoadFailure(error) {
    showErrorLog(error);
}

function onTemplateLoadSuccess(obj) {
    $('#errorLog').html('');
}

function onTemplateLoadTimeout(error) {
    showErrorLog(error);
}

function onErrorReceived(error) {
    showErrorLog(error);
}

function onAdSchedulingComplete(ads) {
    if (!ads.length && !init) {
        $('#errorLog').html('No ads found in VAST Template');
    }
}