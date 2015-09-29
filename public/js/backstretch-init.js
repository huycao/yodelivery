jQuery(document).ready(function($) {
	
       $.backstretch(slides, { fade: 3000,duration: 4000});
	   $.bgt(jt, { fade: 3000,duration: 4000});
});





// ANIMATE-INIT.JS
//--------------------------------------------------------------------------------------------------------------------------------
//This is  JS file that activates element animation effects used in this template*/
// -------------------------------------------------------------------------------------------------------------------------------
// Template Name: TEMPLATE.
// Version: 1.0 Initial Release
// Release Date: 01st Oct 2013
// Author: Designova.
// Website: http://www.designova.net 
// Copyright: (C) 2013 
// -------------------------------------------------------------------------------------------------------------------------------

/*global $:false */
/*global window: false */

(function(){
  "use strict";


$(function ($) {

   //Highlight the navigation on focusing a section
   $('.links li > a').click(function(){
        $('.links li > a').removeClass('active');
        $(this).addClass('active');
    });
   
   
});
// $(function ($)  : ends

})();
//  JSHint wrapper $(function ($)  : ends