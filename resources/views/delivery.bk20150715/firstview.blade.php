<?php
$source ="";
$width ="320";
$height ="416";
if(isset($data['ad'])){
    $source =  $data['ad']->main_source;
    $source =  $data['ad']->$source;
    $width = $data['ad']->width;
    $height = $data['ad']->height;
}
?>
    avlHelperModule.loadAvlStyle();
    function showYoMediaPopupAd_{{$data['zid']}}(s) {
        if(avlInteractModule.isMobile() == false) return false;
        var c_{{$data['zid']}} = avlInteractModule.getCookie('Yomedia_fv_{{$data['zid']}}');
        if (c_{{$data['zid']}} != "1") {
            var a_{{$data['zid']}} = document.getElementById("Yomedia_Full_Banner_{{$data['zid']}}"), b_{{$data['zid']}} = document.getElementById("backgroundId");
            void 0 == a_{{$data['zid']}} && (a_{{$data['zid']}} = domManipulate.create('div', 'Yomedia_Full_Banner_{{$data['zid']}}', 'border: 0px none; margin: 0px; padding: 0px; text-align: left; overflow: visible; position: fixed; z-index: 100000; top: 0px; left: 0px;', ''), document.body.insertBefore(a_{{$data['zid']}}, document.body.childNodes[0]));
            void 0 == b_{{$data['zid']}} && (b_{{$data['zid']}} = domManipulate.create('div', 'backgroundId_{{$data['zid']}}', 'border: 0px none; margin: 0px; padding: 0px; text-align: left; overflow: visible; position: fixed; z-index: 9999; top: 0px; left: 0px;', ''),
                    document.body.insertBefore(b_{{$data['zid']}}, document.body.childNodes[0]));
            document.getElementById("backgroundId_{{$data['zid']}}").setAttribute('onclick', 'closeYoMediaExpand_120()');
            document.getElementById("backgroundId_{{$data['zid']}}").style.width = "100%";
            document.getElementById("backgroundId_{{$data['zid']}}").style.height = "100%";
            document.getElementById("backgroundId_{{$data['zid']}}").style.backgroundColor = 'rgba(0,0,0,0.8)';
            document.getElementById("Yomedia_Full_Banner_{{$data['zid']}}").innerHTML = '<div id = "Yomedia_first_view_banner_{{$data['zid']}}" style = "border: 0 none;position: absolute;"></div>';
            var e_{{$data['zid']}} = document.getElementById("Yomedia_first_view_banner_{{$data['zid']}}");
            var viewportwidth_{{$data['zid']}};
            var viewportheight_{{$data['zid']}};
            if (typeof window.innerWidth != 'undefined') {
                viewportwidth_{{$data['zid']}} = window.innerWidth,
                        viewportheight_{{$data['zid']}} = window.innerHeight
            }
            else if (typeof document.documentElement != 'undefined'
                    && typeof document.documentElement.clientWidth !=
                    'undefined' && document.documentElement.clientWidth != 0) {
                viewportwidth_{{$data['zid']}} = document.documentElement.clientWidth,
                        viewportheight_{{$data['zid']}} = document.documentElement.clientHeight
            } else {
                viewportwidth_{{$data['zid']}} = document.getElementsByTagName('body')[0].clientWidth, viewportheight = document.getElementsByTagName('body')[0].clientHeight
            }
            var left = (viewportwidth_{{$data['zid']}} - {{$width}}) / 2;
            e_{{$data['zid']}}.style.top = "47px";
            e_{{$data['zid']}}.style.left = left + "px";
            e_{{$data['zid']}}.style.width = "{{$width}}px";
            e_{{$data['zid']}}.style.height = "{{$height}}px";
            document.body.style.overflow = "hidden";
            document.getElementById("backgroundId_{{$data['zid']}}").style.width = "100%";
            document.getElementById("backgroundId_{{$data['zid']}}").style.height = "100%";
            document.getElementById("backgroundId_{{$data['zid']}}").style.backgroundColor = 'rgba(0,0,0,0.8)';
            document.getElementById("Yomedia_first_view_banner_{{$data['zid']}}").innerHTML = '<div id = "banner-close_{{$data['zid']}}" onclick = "closeYoMediaPopupAd_{{$data['zid']}}()" style = "width: 40px;height: 40px;background-image: url(http://local.yomedia.com/demo/wap-firstview/data/close_button.png);position: absolute;top: 0;right: 0;z-index: 50000;"></div></div><img src = "{{$source}}" width="{{$width}}" height="{{$height}}"/>';
            document.getElementById("banner-close_{{$data['zid']}}").setAttribute('onclick', 'closeYoMediaPopupAd_{{$data['zid']}}()');
            avlInteractModule.setCookie("Yomedia_fv_{{$data['zid']}}", "1", 1);
            setInterval(closeYoMediaPopupAd_{{$data['zid']}}, 5000);
       }
    }

    function closeYoMediaPopupAd_{{$data['zid']}}() {
        document.getElementById("backgroundId_{{$data['zid']}}").remove();
        document.getElementById("Yomedia_Full_Banner_{{$data['zid']}}").remove();
        document.body.style.overflow = 'auto';
    }
    showYoMediaPopupAd_{{$data['zid']}}(1);


