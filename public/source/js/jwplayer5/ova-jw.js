(function(jwplayer) {

var ovaJWDebug = new function() {
    /*
     * Prints out the debug string to the Javascript console
     */
    this.out = function(output) {
   		try {
      		console.log(output);
   		}
   		catch(error) {}
    }
}

var template = function(_player, _options, div) {

    var _NOT_IMPLEMENTED = "Bridge implementation not available";
    var _nativeImplementation = null;

    /*
     * PLUGIN STARTUP
     */

    function _initialise() {
   		ovaJWDebug.out("Initialising the OVA JS plugin - version 1.0.0 (build 2)");

        if(_player.getRenderingMode() == "html5") {
            /*
			 * if(eval("typeof NativeOVAImplementation == 'object'") == false) {
	    	 *	 ovaJWDebug.out("OVA JS plugin operating in HTML5 mode - loading OVA for JW5 HTML5 code");
	         *  	 $.getScript('./ova-jw-native.js',
			 *       function() {
	         *		   	ovaJWDebug.out("OVA for JW5 HTML5 code loaded - instantiating native implementation");
	         *  			_nativeImplementation = new NativeOVAImplementation(_player, _options);
	         *  			_nativeImplementation.start();
	         *  		}
	         *  	 );
             * }
             */
        }
        else ovaJWDebug.out("OVA JS plugin operating in 'bridge' mode to the OVA SWF API");
    }


    /*
     * OVA PUBLIC API
     */

    /*
     * Method
     */
    this.play = function(index, absolutePosition) {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaPlay(index, absolutePosition);
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    }

    /*
     * Method
     */
    this.getVersion = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaGetVersion();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.enableAds = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaEnableAds();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.disableAds = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaDisableAds();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.scheduleAds = function(playlist, newConfig) {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaScheduleAds(playlist, newConfig);
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.loadPlaylist = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaLoadPlaylist();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.getAdSchedule = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
                
               document.getElementById(_player.id).ovaGetAdSchedule();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };
    /*
     * Method
     */
    this.getStreamSequence = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaGetStreamSequence();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };
    /*
     * Method
     */
    this.setDebugLevel = function(levels) {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaSetDebugLevel(levels);
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.getDebugLevel = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaGetDebugLevel();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.skipAd = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaSkipAd();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.clearOverlays = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaClearOverlays();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.showOverlay = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaShowOverlay();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.hideOverlay = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaHideOverlay();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.enableAPI = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaEnableAPI();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.disableAPI = function() {
        if(_player.getRenderingMode() == 'flash') {
            try {
               document.getElementById(_player.id).ovaDisableAPI();
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
        }
    };

    /*
     * Method
     */
    this.setActiveLinearAdVolume = function(volume) {
        if(_player.getRenderingMode() == 'flash') {
            try {
               return document.getElementById(_player.id).ovaSetActiveLinearAdVolume(volume);
            }
            catch(error) {
               ovaJWDebug.out(error);
            }
        } else {
            ovaJWDebug.out(_NOT_IMPLEMENTED);
            return false;
        }
    };

    _player.onReady(_initialise);
};


/** Register the plugin with JW Player. **/
jwplayer().registerPlugin('ova', template,'ova-jw.swf');

})(jwplayer);

