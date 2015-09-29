var avlDomain = 'delivery.yomedia.vn';
var avlProtocal = (document.location.protocol == 'https:') ? 'https://' : 'http://';
var avlDelivery = avlProtocal + avlDomain + '/conversion';

var avlInitModule = new function(){
	return {
		init : function(conversionID, avlParam){
			if (typeof conversionID !== 'undefined' && typeof avlParam !== 'undefined') {
				var data = '';
				data = JSON.stringify(avlParam);
				if (data == '{}') {
					data = '';
				}
				
				var link = avlDelivery + '?cid=' + conversionID + '&param=' + encodeURI(data);
				avlInitModule.embedJs(link);
			}				
		},
		embedJs: function(src){
	        var isAble = true;
	        if (navigator.appVersion.indexOf("MSIE 7.") != -1) isAble = false;
	        if (isAble) {
	            document.write('<script type="text/javascript" src="' + src + '"></script>')
	        }
		},
		addSource: function (c, s) {
			var d = '<div id="yomedia-conversion-' + c + '">';
			d += s;
			d += '</div>';
			document.write(d);
		}
	}
}

if( typeof _avlDemo == 'undefined' ){
	avlInitModule.init(_conversionID, _avlParam);
}


