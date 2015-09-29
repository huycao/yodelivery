//JSHint Validated Custom JS Code by Designova

/*global $:false */
/*global window: false */
jQuery(document).ready(function($) {


(function(){
  "use strict";





//MASONRY PORTFOLIO INIT:
$(function () {

    var $container = $('#container');

    $container.isotope({
        itemSelector: '.element',
        layoutMode: 'masonry'
    });


    var $optionSets = $('#options .option-set'),
        $optionLinks = $optionSets.find('a');

    $optionLinks.click(function () {
        var $this = $(this);
        // don't proceed if already selected
        if ($this.hasClass('selected')) {
            return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');

        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
        var changeLayoutMode;
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[key] = value;
        if (key === 'layoutMode' && typeof changeLayoutMode === 'function') {
            // changes in layout modes need extra logic
            changeLayoutMode($this, options);
        } else {
            // creativewise, apply new options
            $container.isotope(options);
        }

        return false;
    });
	
	
	//active menu
	$('.nav a').click(function(){
		$('.nav a.active').removeClass('active');
		$(this).addClass('active');
	})
	

	//scroll 	
	$.scrollify({
		section:".panel"
	});
	

	$(".scroll").click(function(e) {
		e.preventDefault();
		$.scrollify("move",$(this).attr("href"));
	});

});






/*===========================================================*/
/*  Colorbox
/*===========================================================*/
$(function () {
    
    var viewportHeight = $(window).height();
    var introMargin = (viewportHeight / 3) - (viewportHeight / 12);
    $('#intro').height(viewportHeight);
    $('.promo-one').css('margin-top', introMargin);
    //Examples of how to assign the ColorBox event to elements
    $(".zoom-info").colorbox({
        rel: 'group1',
        transition: "fade",
        speed: 1700,
        onComplete: function () {
            $('.flexslider').flexslider({
                animation: "slide",
                controlNav: false,
                directionNav: true

            });
        }
    });

    //Sliding Navigation
    // $('#simple-menu').sidr();
    // $('#sidr ul li').click(function(){
    //     $('#sidr ul li').removeClass('active');
    //     $(this).addClass('active');
    // });
    // //Sliding Navigation - TouchWipe Extension
    // $(window).touchwipe({
    //     wipeLeft: function() {
    //     // Close
    //     $.sidr('close', 'sidr-main');
    // },
    // wipeRight: function() {
    //     // Open
    //     $.sidr('open', 'sidr-main');
    //     },
    //     preventDefaultEvents: false
    // });

//Blog paginate
  $('.paginate a').addClass('btn-live');
  $('#post-comment').addClass('btn btn-live');
  $('#reply-title').addClass('blog-caps');

  $.ajaxSetup({cache:false});
  $('.paginate a').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    var height = $('#ajax-container').height();
   $(this).addClass('btn-live');    
    $('#ajax-container').fadeOut(500).load(link + ' #ajax-inner', function(){ $('#ajax-container').fadeIn(300);  $('.paginate a').addClass('btn btn-live'); });
  });


    $('.band').mouseenter(function () {
        var pageInd = $(this).attr('id');
        $('#navigation ul li > a').removeClass('lighted');
        $('#' + pageInd + '-linker').addClass('lighted');
    });

    $('#navigation ul li > a').click(function () {
        $('#navigation ul li > a').removeClass('lighted');
        $(this).addClass('lighted');
    });



    //Triggering Scroll-down Navigation
    $('#navigation ul').localScroll(9500);
    $('#sidr ul').localScroll(9500);



    //=======================
    //WAYPOINTS - INTERACTION
    //=======================

    //Showing Tool Tip on Menu Item MouseOver
    $("[data-rel=tooltip]").tooltip({placement: 'bottom'});


    //Showing Navigation Tool Tip on Entering Each Page:
    $('.band').mouseenter(function(){
        var pageInIndex = $(this).attr('id');
        $('#'+pageInIndex+'-linker').tooltip('show');
    });
    $('.band').mouseleave(function(){
        var pagesOutIndex = $(this).attr('id');
        $('#'+pagesOutIndex+'-linker').tooltip('hide');
    });

    //fail safe
    $('.folio-item').find('.folio-trigger-icon').fadeOut();
    $('.folio-item').find('.titles').fadeOut();

    $('.folio-item').mouseenter(function () {
        $(this).find('img').css('opacity', '0.2');
        $(this).find('.folio-trigger-icon').fadeIn();
        $(this).find('.titles').fadeIn();
    });

    $('.folio-item').mouseleave(function () {
        $('.folio-item').find('.titles').fadeOut();
        $(this).find('.folio-trigger-icon').fadeOut();
        $('.folio-item').find('img').css('opacity', '1');
    });


    $('.element > img, .service-item, .about-feat').mouseleave(function () {
        $(this).addClass('remove-zoom');
    });
});


})();

});