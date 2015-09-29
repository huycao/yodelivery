<?php
$source          = "";
$destination_url = "#";
$source          = $data['ad']->main_source;
$source          = $data['ad']->$source;
$height          = $data['ad']->height;
$width           = $data['ad']->width;
$eidtype         = substr($data['element_id'],0,1);
$eid             = substr($data['element_id'],1);
?>
avlHelperModule.loadAvlStyle();
function showYoMediaPopupAd_{{$data['zid']}}(s) {
    var a_{{$data['zid']}} = document.getElementById("YomediaInpage_{{$data['zid']}}");
    if(a_{{$data['zid']}} == null) {
        @if($eidtype == '#')
        var content_{{$data['zid']}} = document.getElementById('{{$eid}}');
        @else
        var content_{{$data['zid']}} = document.getElementsByClassName('{{$eid}}')[0];
        @endif
        e_{{$data['zid']}} = content_{{$data['zid']}}.childNodes;
        var p_{{$data['zid']}} = 0;
        for(var i_{{$data['zid']}}=0; i_{{$data['zid']}} < e_{{$data['zid']}}.length; i_{{$data['zid']}}++) {
            if(e_{{$data['zid']}}[i_{{$data['zid']}}].clientHeight > 0){
                p_{{$data['zid']}} = p_{{$data['zid']}}+ e_{{$data['zid']}}[i_{{$data['zid']}}].clientHeight;
            }
            
            if(p_{{$data['zid']}} >= (content_{{$data['zid']}}.clientHeight / 2)){
                if(typeof(content_{{$data['zid']}}.childNodes[i_{{$data['zid']}} +1]) != 'undefined'){
                    var eleToInsert = i_{{$data['zid']}} +1;
                }
                else{
                    var eleToInsert = i_{{$data['zid']}};
                }

                a_{{$data['zid']}} = domManipulate.create('div', 'YomediaInpage_{{$data['zid']}}', '', ''), content_{{$data['zid']}}.insertBefore(a_{{$data['zid']}}, content_{{$data['zid']}}.childNodes[eleToInsert]);
                break;
            }
        }
    }
        var container_width = content_{{$data['zid']}}.clientWidth;
        a_{{$data['zid']}}.style.opacity = 1;
        a_{{$data['zid']}}.style.position = 'relative';
        a_{{$data['zid']}}.style.overflow = 'hidden';
        a_{{$data['zid']}}.style.zIndex  = '0';
        a_{{$data['zid']}}.style.width = screen.width + "px";
        a_{{$data['zid']}}.style.display = 'block';
        a_{{$data['zid']}}.style.visibility = 'visible';
        a_{{$data['zid']}}.style.height =  (container_width / {{$width}} * {{$height}} + 40) +'px';
        a_{{$data['zid']}}.style.background = 'transparent';
    var rs = '';
    if(avlInteractModule.isMobile() == true){
    rs = '<div id="YomediaInpageContent_{{$data['zid']}}" style="display: block; opacity: 1; overflow: hidden; margin: 0px auto; position: fixed; z-index: 1; bottom: 0px; left:0;right:0;max-width: 100%;background: transparent;"><a style="display: block;" href="{{$destination_url}}" target="_blank"><img id="yomedia-inpage-banner" style="width:100%; max-width: 100%;" src="{{$source}}"></a></div>';
    rs += '<div id="more-view_{{$data['zid']}}" style="opacity: 1; float: right; z-index: 3; clear: both; position: absolute; bottom: 0px; left: 0px; width: 100%; text-align: center; background: transparent;height: 30px;"><a href="#" target="_blank" style="color:#FFF;font-size: 1.3em;background: #CCC;padding: 5px 13px;border-radius: 10px;margin: 5px;height: 18px;">Đọc tiếp</a></div>';
    domManipulate.getElid('YomediaInpage_{{$data['zid']}}').innerHTML = rs;
    domManipulate.getElid('YomediaInpage_{{$data['zid']}}').style.display = 'block';

    {{-- domManipulate.getElid('YomediaInpage_{{$data['zid']}}').style.height = domManipulate.getElid('YomediaInpageContent_{{$data['zid']}}').clientHeight +'px'; --}}
    

    }else {
    domManipulate.getElid('YomediaInpage_{{$data['zid']}}').innerHTML = rs;
    domManipulate.getElid('YomediaInpage_{{$data['zid']}}').style.display = 'none';
    }

}
showYoMediaPopupAd_{{$data['zid']}}(1);