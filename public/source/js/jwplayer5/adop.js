var adopsTools = adopsTools || {};
var wProxy;
var viewer;

/** @param {jQuery} $ jQuery Object */
!function($, window, document, _undefined)
{

	
	AdOpsTools = function($container)
	{
		this.wProxy;
		this.timeStart;
		this.timeEnd;
		this.ovaJson;
		this.schedule;
		this.inputType;
		this.creativeType;
		this.Ad;
		this.adXml;
	    this.log = function(a) {
	    	try {
	    		console.log(a);
	    	} catch(e) {}
	    };
	    this.error = function(e) {
	    	try {
	    		console.error(e);
	    	} catch(e) {}
	    };

	};
	
	AdOpsTools.prototype.init = function()
	{
		if ($('div.adopstools_adtag_preview').is('*')) {
			adopsTools.previewListeners();
		}
		if ($('div.adopstools_url_encoder').is('*')) {
			adopsTools.urlencodeListeners();
			$('textarea#ctrl_url_string').blur(function(evt) {
				$('textarea#ctrl_url_string').val($('textarea#ctrl_url_string').val().trim());
			
				});
		}
		if ($('div.adopstools_screengrab')) {
			adopsTools.screengrabListeners();
		}
		if ($('div.adopstools_vast_validator').is('*')) {
			adopsTools.vastListeners();
			
		}		
	};
	
	AdOpsTools.prototype.buildOVAConfiguration = function(schedule)
	{
		var config = {
			
			   "debug": { "levels":  "fatal,config,vast_template,api,playlist,display_events"  },
			   "autoPlay": true,
			   "canFireEventAPICalls": true,
			   "ads": {
			      "companions": {
			      	"regions" : [
				          { "id":"compAd300x250", "width":300, "height":250 },
          			

				          { "id":"compAd728x90", "width":728, "height":90 }
          				]
          				
			      },
			      "schedule": schedule
			   }
			};
		
		adopsTools.ovaJson = config;
	};
	
	
	AdOpsTools.prototype.vastListeners = function ()
	{
		$('form.vastValidator input[type=radio]').click(function(evt){
					var val = $(this).val();
					var sib = $($(this).siblings()[0]);
					adopsTools.inputType = val;

					if ($('form.vastValidator input[name="inputType"][value="'+ val +'"]').is(':checked')) {
						sib.attr('disabled', '').removeClass('disabled');
						if (val == 'vastUrl')
						{
							$('form.vastValidator textarea[name=xmlString]').val('').attr('disabled', 'disabled').addClass('disabled');
						} else {
							$('form.vastValidator input[name=urlString]').val('').attr('disabled', 'disabled').addClass('disabled');	
						}
					}				
		});		

		jwplayer('videoPlayer').setup(
			{
				flashplayer: '/swf/player.swf',
				plugins : {
					'/swf/ova-jw.js' : {
					      "canFireEventAPICalls": true,
			              "ads": {
			              },
			              "debug": { 
			                  "levels": "fatal, config, vast_template, vpaid, http_calls, tracking_events, playlist, api" 
			              }
     				}
 				},
				file : '/data/adopstools/video/demo-video.mp4',
				image : '/data/adopstools/video/demo-video.jpg',
				"controlbar.position": "bottom",
				width: 325		
			
		});
		
		 $('form.vastValidator input[name=urlString]').bind('blur', function(evt) {
				var proto = /^http/i;
				var val = $(evt.currentTarget).val();
				if (val.length == 0) return false;
				if (proto.test($(evt.currentTarget).val()) === false)
				{
					$(evt.currentTarget).val('http://'+ $(evt.currentTarget).val());
				}
				
		});		
		
		$('form.vastValidator a.vastExample').click(function(evt) {
			evt.preventDefault();
			$('form.vastValidator input[name=urlString]').val($(this).attr('href'));
			$('form.vastValidator input[name=urlString]').trigger('change');
			$('form.vastValidator input[name="creativeType"][value="'+ $(this).attr('creativetype') +'"]').attr('checked', true);
			$('form.vastValidator input[name="inputType"][value="vastUrl"]').attr('checked', true).trigger('click');
			
			$('form.vastValidator').submit();
			
		});
	
		$('form.vastValidator').bind('submit', function(evt) {
			
				evt.preventSubmit = true;
				evt.preventDefault();
				adopsTools.inputType = $('form.vastValidator input[name=inputType]:checked').val();
				adopsTools.creativeType = $('form.vastValidator input[name=creativeType]:checked').val();

				if (adopsTools.inputType == 'vastUrl')
				{
					adopsTools.displayAd($('form.vastValidator input[name=urlString]').val());
				} else {
					adopsTools.displayXmlAd($('form.vastValidator textarea[name=xmlString]').val());
				}
			
		});

			$('form.vastValidator').bind('gsubmit', function(e) { 
							
						
							XenForo.ajax(
									'/adops-tools/ajax/parse-vasturl', 
									{ 
											vastUrl : vastUrl
									},
									function (ajaxData, textStatus)
									{		
										var schedule = [];
										$('div.additionalInfo div.xmlError').empty();
										$('div.additionalInfo div.xmlOutput').empty();
										$('div.additionalInfo div.vastInfo').empty();
										$('div.additionalInfo div.mediaFile').empty();
										$('div.additionalInfo div.xmlVideoClick').empty();
										$('div.additionalInfo div.companionHTML').empty();
										$('div.additionalInfo div.xmlImpression').empty();
										$('div.additionalInfo div.xmlTracker').empty();
										if (ajaxData.success == false) {
									
											$('div.additionalInfo div.xmlError').html(ajaxData.error).addClass('vastError');
											return false;
										}
									
										$('div.additionalInfo div.xmlError').html('').removeClass('vastError');
										$('div.additionalInfo div.xmlOutput').html('<pre>'+ ajaxData.vast +'</pre>');
										$('div.additionalInfo div.vastInfo').html('Version: '+ ajaxData.version +'<br \n>Creative Type:'+ ajaxData.ad_type);
										$('div.additionalInfo div.mediaFile').html('<a href="'+ ajaxData.mediafile +'" target="_blank">'+ ajaxData.mediafile +'</a>');
										$('div.additionalInfo div.xmlVideoClick').html('<a href="'+ ajaxData.video_click +'" target="_blank">'+ ajaxData.video_click +'</a>');
										$.each(ajaxData.companions, function(idx, itm) {
											$('div.additionalInfo div.companionHTML').append('<div><pre>'+ itm +'</pre></div>');
										});
										$.each(ajaxData.impressions, function(idx, itm) {
											$('div.additionalInfo div.xmlImpression').append('<p>'+ itm +'</p>');
										});
										if (ajaxData.trackers.length > 0) {
											var html = '<table>';
											
											$.each(ajaxData.trackers, function(idx, itm) {
												html += '<tr><td>'+ itm['event'] +'</td><td>'+ itm[0] + '</td></tr>';
											});
											html += '</table>';
											$('div.additionalInfo div.xmlTracker').append(html);
										}
										
										if (ajaxData.ad_type == 'Linear') {
											schedule = [ { "position" : "pre-roll", "tag": vastUrl } ];
										} else {
											schedule = [ { 	"position" : "auto", 
															"duration": "recommended:15", 
															"startTime": "00:00:05", 
															"server" : 
																		{ 	"type" : "direct", 
																			"tag" : vastUrl 
																		}
														}];
										}
										adopsTools.buildOVAConfiguration(schedule);
									
										jwplayer('videoPlayer').getPlugin("ova").scheduleAds(null, adopsTools.ovaJson);
										jwplayer('videoPlayer').play();
									}
									);

							
				});
		
	};
	
	AdOpsTools.prototype.onTemplateLoadFailure = function(error)
	{
		$('div.additionalInfo div.xmlError').html(error).addClass('vastError');
	};
	
	AdOpsTools.prototype.onLinearAdScheduled = function (Ad)
	{
		adopsTools.Ad = Ad;
		$('div.additionalInfo div.vastInfo').html('Version: '+ Ad.adVersion +'<br \n>Creative Type:'+ Ad.type);
		$('div.additionalInfo div.mediaFile').html('<a href="'+ Ad.linearAd.mediaFiles[0].url +'" target="_blank">'+ Ad.linearAd.mediaFiles[0].url +'</a>');
		$.each(Ad.linearAd.clickThroughs, function(idx, itm){
			$('div.additionalInfo div.xmlVideoClick').append('<a href="'+ itm.url +'">'+ itm.url +'</a>');
		});
		$.each(Ad.companionAds, function(idx, itm) {
			$('div.additionalInfo div.companionHTML').append('<div><pre>'+ adopsTools.htmlentities(itm.codeBlock) +'</pre></div>');
		});
		
									/*
										$.each(ajaxData.impressions, function(idx, itm) {
											$('div.additionalInfo div.xmlImpression').append('<p>'+ itm +'</p>');
										}); */
		if (Ad.linearAd.trackingEvents.length > 0) {
			var html = '<table>';
											
			$.each(Ad.linearAd.trackingEvents, function(idx, itm) {
					html += '<tr><td>'+ itm.eventType +'</td><td>'+ itm.urls[0].url + '</td></tr>';
			});
			html += '</table>';
			$('div.additionalInfo div.xmlTracker').append(html);
		}
	};
	
	AdOpsTools.prototype.onNonLinearAdScheduled = function (Ad)
	{
		adopsTools.Ad = Ad;
		$('div.additionalInfo div.vastInfo').html('Version: '+ Ad.adVersion +'<br \n>Creative Type:'+ Ad.type);
		$('div.additionalInfo div.mediaFile').html('<a href="'+ Ad.linearAd.mediaFiles[0].url +'" target="_blank">'+ Ad.linearAd.mediaFiles[0].url +'</a>');
		$.each(Ad.linearAd.clickThroughs, function(idx, itm){
			$('div.additionalInfo div.xmlVideoClick').append('<a href="'+ itm.url +'">'+ itm.url +'</a>');
		});
		$.each(Ad.companionAds, function(idx, itm) {
			$('div.additionalInfo div.companionHTML').append('<div><pre>'+ adopsTools.htmlentities(itm.codeBlock) +'</pre></div>');
		});
		
		
		if (Ad.linearAd.trackingEvents.length > 0) {
			var html = '<table>';
											
			$.each(Ad.linearAd.trackingEvents, function(idx, itm) {
					html += '<tr><td>'+ itm.eventType +'</td><td>'+ itm.urls[0].url + '</td></tr>';
			});
			html += '</table>';
			$('div.additionalInfo div.xmlTracker').append(html);
		}
	};
	AdOpsTools.prototype.onTemplateLoadSuccess = function(tpl) {
			adopsTools.adXml = decodeURIComponent(tpl);
			$('dl.vastXml div.xmlOutput').empty().append('<pre>'+ adopsTools.htmlentities(decodeURIComponent(tpl)) +'</pre>');
	};
	AdOpsTools.prototype.onImpressionEvent = function(evt, forced)
	{	
	   if(evt != null) {	    
	       $('.videoPreview .trackerCtner div#impression').addClass('selected');
	       $('div.additionalInfo div.xmlImpression').append('<p>'+ evt.url +'</p>');
									/*
										$.each(ajaxData.impressions, function(idx, itm) {
											$('div.additionalInfo div.xmlImpression').append('<p>'+ itm +'</p>');
										}); */

	   }
		
	};
	AdOpsTools.prototype.onTrackingEvent = function (event) {
	   if(event != null) {
	       $('.videoPreview .trackerCtner div#' + event.eventType).addClass('selected');
	   }
	};
	
	AdOpsTools.prototype.displayAd = function(vastUrl)
	{
		adopsTools.Ad = {};
		if (adopsTools.creativeType == 'linear') {
			schedule = [ { "position" : "pre-roll", "tag": vastUrl } ];
		} else {
			schedule = [ { 	"position" : "auto", 
							"duration": "recommended:15", 
							"startTime": "00:00:03", 
							"server" : 
										{ 	"type" : "direct", 
											"tag" : vastUrl 
										}
						}];
		}
		adopsTools.buildOVAConfiguration(schedule);
		$('div.additionalInfo div.xmlError').empty().removeClass('vastError');
		$('div.additionalInfo div.xmlOutput').empty();
		$('div.additionalInfo div.vastInfo').empty();
		$('div.additionalInfo div.mediaFile').empty();
		$('div.additionalInfo div.xmlVideoClick').empty();
		$('div.additionalInfo div.companionHTML').empty();
		$('div.additionalInfo div.xmlImpression').empty();
		$('div.additionalInfo div.xmlTracker').empty();	
		$('div.videoPreview dl.trackerCtner dd div.trackerToggle').removeClass('selected');	
		jwplayer('videoPlayer').getPlugin("ova").scheduleAds(null, adopsTools.ovaJson);
		jwplayer('videoPlayer').play();
	};
	
	AdOpsTools.prototype.displayXmlAd = function(vastXml)
	{
		var xmlDom = $($.parseXML(vastXml));
		adopsTools.adXml = xmlDom;
		if (xmlDom.find('Creatives Creative NonLinearAds').is('*')) {
			schedule = [ { 	"position" : "auto", 
							"duration": "recommended:15", 
							"startTime": "00:00:03", 
							"server" : 
										{ 	"type" : "Inject", 
											"tag" : vastXml 
										}
						}];			
						
			$('form.vastValidator input[name=creativeType][value=nonlinear]').attr('checked', 'true');
		} else {
			schedule = [ { "position" : "pre-roll", "server" : 
													{	"tag": vastXml,
														"type" : "Inject" }
						} ];
			$('form.vastValidator input[name=creativeType][value=linear]').attr('checked', 'true');
		}
		adopsTools.Ad = {};
		adopsTools.buildOVAConfiguration(schedule);
		$('div.additionalInfo div.xmlError').empty().removeClass('vastError');
		$('div.additionalInfo div.xmlOutput').empty();
		$('div.additionalInfo div.vastInfo').empty();
		$('div.additionalInfo div.mediaFile').empty();
		$('div.additionalInfo div.xmlVideoClick').empty();
		$('div.additionalInfo div.companionHTML').empty();
		$('div.additionalInfo div.xmlImpression').empty();
		$('div.additionalInfo div.xmlTracker').empty();	
		$('div.videoPreview dl.trackerCtner dd div.trackerToggle').removeClass('selected');	
		jwplayer('videoPlayer').getPlugin("ova").scheduleAds(null, adopsTools.ovaJson);
		jwplayer('videoPlayer').play();
	}	
	

	AdOpsTools.prototype.screengrabListeners  = function()
	{
		$('div.adopstools_screengrab form.xenForm').submit(function(evt) {
				evt.preventSubmit = true;
				evt.preventDefault();
				return false;				
		});
		$('div.adopstools_screengrab input#capture').click(function(evt) {
			$(evt.currentTarget).attr('disabled', 'true');
			var urlStr = $('div.adopstools_screengrab input#ctrl_url_string').val().trim();
			if (urlStr != '') {

				XenForo.ajax(
						'/adops-tools/ajax/screen-grab', 
						{ 
								websiteUrl : urlStr
						},
						function (ajaxData, textStatus)
						{
							if (textStatus == 'success')
							{
								if (!$('div.adopstools_screengrab li.primaryContent div.urlOutput img.screengrab').is('*')) {
									var scLink = document.createElement('a');
									$(scLink).attr('class', 'LbTrigger');
									$(scLink).attr('data-href', 'misc/lightbox');
									$(scLink).attr('target', '_blank');
									$(scLink).attr('href', ajaxData.screengrab);
									
									var scImg = document.createElement('img');
									$(scImg).attr('src', ajaxData.screengrab);
									$(scImg).attr('class', 'screengrab');
									$(scImg).css('width', '800px');
									$(scImg).attr('class', 'LbImage')
									$(scLink).append(scImg);
									
									$('div.adopstools_screengrab li.primaryContent div.urlOutput').empty().append(scLink);
									XenForo.register('a.LbTrigger', 'XenForo.LightBoxTrigger');
								} else {
									$('div.adopstools_screengrab li.primaryContent div.urlOutput a.LbTrigger').attr('htef', ajaxData.screengrab);
									$('div.adopstools_screengrab li.primaryContent div.urlOutput img.screengrab').attr('src', ajaxData.screengrab);
								}
								$($('div.adopstools_screengrab li.primaryContent')[2]).show();	
								$(evt.currentTarget).attr('disabled', '');					
							}
							
						}
				);
				
			}
		});
	}
	
	AdOpsTools.prototype.urlencodeListeners = function()
	{
		$('input#urlEncode').click(function(evt){
			$('textarea#ctrl_url_string').val(encodeURIComponent($('textarea#ctrl_url_string').val().trim()));
		});
		$('input#urlDecode').click(function(evt){
			$('textarea#ctrl_url_string').val(decodeURIComponent($('textarea#ctrl_url_string').val().trim()));
		});		
	};	
	AdOpsTools.prototype.previewListeners = function()
	{

		(function() {
		       	var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		        po.src = 'http://chat.4i.net/js/porthole.min.js';
		        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		})();
		
		if ($('div.adopstoolsContent div.adTagPreview').is('*')) {
			var previewHar = document.createElement('iframe');
			
			$(previewHar).attr('class', 'harFrame');
			$(previewHar).attr('src', '/adops-tools/harviewer?inputUrl='+ $('div.adopstoolsContent div.har').attr('data-har') + '&expand=true');
			$(previewHar).attr('width', '100%');
			$(previewHar).attr('height', '300');
			$(previewHar).attr('frameborder', '0');
			$(previewHar).attr('scrolling', 'no');
			$(previewHar).attr('allowtransparency', 'true');
			$(previewHar).attr('style', 'min-height: 250px; _height: 250px;');
			$('div.adopstoolsContent div.har').append(previewHar);
			$('div.adopstoolsContent div.adTagPreview').load(function() {});
		}
		
	};
	AdOpsTools.prototype.htmlentities = function (string, quote_style, charset, double_encode) {

	    var hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style),
	        symbol = '';
	    string = string == null ? '' : string + '';
	
	    if (!hash_map) {
	        return false;
	    }
	    
	    if (quote_style && quote_style === 'ENT_QUOTES') {
	        hash_map["'"] = '&#039;';
	    }
	    
	    if (!!double_encode || double_encode == null) {
	        for (symbol in hash_map) {
	            if (hash_map.hasOwnProperty(symbol)) {
	                string = string.split(symbol).join(hash_map[symbol]);
	            }
	        }
	    } else {
	        string = string.replace(/([\s\S]*?)(&(?:#\d+|#x[\da-f]+|[a-zA-Z][\da-z]*);|$)/g, function (ignore, text, entity) {
	            for (symbol in hash_map) {
	                if (hash_map.hasOwnProperty(symbol)) {
	                    text = text.split(symbol).join(hash_map[symbol]);
	                }
	            }
	            
	            return text + entity;
	        });
	    }

    	return string;
	};
	AdOpsTools.prototype.get_html_translation_table = function (table, quote_style) {

	    var entities = {},
	        hash_map = {},
	        decimal;
	    var constMappingTable = {},
	        constMappingQuoteStyle = {};
	    var useTable = {},
	        useQuoteStyle = {};
	
	    // Translate arguments
	    constMappingTable[0] = 'HTML_SPECIALCHARS';
	    constMappingTable[1] = 'HTML_ENTITIES';
	    constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
	    constMappingQuoteStyle[2] = 'ENT_COMPAT';
	    constMappingQuoteStyle[3] = 'ENT_QUOTES';
	
	    useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
	    useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT';
	
	    if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
	        throw new Error("Table: " + useTable + ' not supported');
	        // return false;
	    }
	
	    entities['38'] = '&amp;';
	    if (useTable === 'HTML_ENTITIES') {
	        entities['160'] = '&nbsp;';
	        entities['161'] = '&iexcl;';
	        entities['162'] = '&cent;';
	        entities['163'] = '&pound;';
	        entities['164'] = '&curren;';
	        entities['165'] = '&yen;';
	        entities['166'] = '&brvbar;';
	        entities['167'] = '&sect;';
	        entities['168'] = '&uml;';
	        entities['169'] = '&copy;';
	        entities['170'] = '&ordf;';
	        entities['171'] = '&laquo;';
	        entities['172'] = '&not;';
	        entities['173'] = '&shy;';
	        entities['174'] = '&reg;';
	        entities['175'] = '&macr;';
	        entities['176'] = '&deg;';
	        entities['177'] = '&plusmn;';
	        entities['178'] = '&sup2;';
	        entities['179'] = '&sup3;';
	        entities['180'] = '&acute;';
	        entities['181'] = '&micro;';
	        entities['182'] = '&para;';
	        entities['183'] = '&middot;';
	        entities['184'] = '&cedil;';
	        entities['185'] = '&sup1;';
	        entities['186'] = '&ordm;';
	        entities['187'] = '&raquo;';
	        entities['188'] = '&frac14;';
	        entities['189'] = '&frac12;';
	        entities['190'] = '&frac34;';
	        entities['191'] = '&iquest;';
	        entities['192'] = '&Agrave;';
	        entities['193'] = '&Aacute;';
	        entities['194'] = '&Acirc;';
	        entities['195'] = '&Atilde;';
	        entities['196'] = '&Auml;';
	        entities['197'] = '&Aring;';
	        entities['198'] = '&AElig;';
	        entities['199'] = '&Ccedil;';
	        entities['200'] = '&Egrave;';
	        entities['201'] = '&Eacute;';
	        entities['202'] = '&Ecirc;';
	        entities['203'] = '&Euml;';
	        entities['204'] = '&Igrave;';
	        entities['205'] = '&Iacute;';
	        entities['206'] = '&Icirc;';
	        entities['207'] = '&Iuml;';
	        entities['208'] = '&ETH;';
	        entities['209'] = '&Ntilde;';
	        entities['210'] = '&Ograve;';
	        entities['211'] = '&Oacute;';
	        entities['212'] = '&Ocirc;';
	        entities['213'] = '&Otilde;';
	        entities['214'] = '&Ouml;';
	        entities['215'] = '&times;';
	        entities['216'] = '&Oslash;';
	        entities['217'] = '&Ugrave;';
	        entities['218'] = '&Uacute;';
	        entities['219'] = '&Ucirc;';
	        entities['220'] = '&Uuml;';
	        entities['221'] = '&Yacute;';
	        entities['222'] = '&THORN;';
	        entities['223'] = '&szlig;';
	        entities['224'] = '&agrave;';
	        entities['225'] = '&aacute;';
	        entities['226'] = '&acirc;';
	        entities['227'] = '&atilde;';
	        entities['228'] = '&auml;';
	        entities['229'] = '&aring;';
	        entities['230'] = '&aelig;';
	        entities['231'] = '&ccedil;';
	        entities['232'] = '&egrave;';
	        entities['233'] = '&eacute;';
	        entities['234'] = '&ecirc;';
	        entities['235'] = '&euml;';
	        entities['236'] = '&igrave;';
	        entities['237'] = '&iacute;';
	        entities['238'] = '&icirc;';
	        entities['239'] = '&iuml;';
	        entities['240'] = '&eth;';
	        entities['241'] = '&ntilde;';
	        entities['242'] = '&ograve;';
	        entities['243'] = '&oacute;';
	        entities['244'] = '&ocirc;';
	        entities['245'] = '&otilde;';
	        entities['246'] = '&ouml;';
	        entities['247'] = '&divide;';
	        entities['248'] = '&oslash;';
	        entities['249'] = '&ugrave;';
	        entities['250'] = '&uacute;';
	        entities['251'] = '&ucirc;';
	        entities['252'] = '&uuml;';
	        entities['253'] = '&yacute;';
	        entities['254'] = '&thorn;';
	        entities['255'] = '&yuml;';
	    }
	
	    if (useQuoteStyle !== 'ENT_NOQUOTES') {
	        entities['34'] = '&quot;';
	    }
	    if (useQuoteStyle === 'ENT_QUOTES') {
	        entities['39'] = '&#39;';
	    }
	    entities['60'] = '&lt;';
	    entities['62'] = '&gt;';
	
	
	    // ascii decimals to real symbols
	    for (decimal in entities) {
	        if (entities.hasOwnProperty(decimal)) {
	            hash_map[String.fromCharCode(decimal)] = entities[decimal];
	        }
	    }
	
	    return hash_map;
	} 
		
}

(jQuery, this, document);

adopsTools = new AdOpsTools();
XenForo.adopsTools = adopsTools;
$(window).load(adopsTools.init);

function onTemplateLoadSuccess(tpl) {
	adopsTools.onTemplateLoadSuccess(tpl);
}

function onLinearAdScheduled(ad) {
	adopsTools.onLinearAdScheduled(ad);
}

function onTemplateLoadError(error)
{
	adopsTools.onTemplateLoadFailure(error);
}


function onTemplateLoadFailure(error)
{
	adopsTools.onTemplateLoadFailure(error);
}

function onErrorReceived(error)
{
	adopsTools.onErrorReceived(error);
}

function onNonLinearAdScheduled(ad)
{
	adopsTools.onNonLinearAdScheduled(ad);
}

function onImpressionEvent(event, forced) {
	adopsTools.onImpressionEvent(event, forced);

}

function onTrackingEvent(event) {
	adopsTools.onTrackingEvent(event);
}

