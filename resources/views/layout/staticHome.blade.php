<html lang="en-US" class="csstransforms csstransforms3d csstransitions skrollr ">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta property="og:title" content=""/>
	<meta property="og:description" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:video" content=""/>
	<meta property="og:type" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content="" />
	<meta name="twitter:card" content="" />
	<meta name="keywords" content=""/>
	<title>Adnetwork YoMedia</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<link rel="stylesheet" id="bootstrap-css" href="/public/css/bootstrap.css" type="text/css" media="all">
	<link rel="stylesheet" id="bootstrap-responsive-css" href="/public/css/bootstrap-responsive.css" type="text/css" media="all">
	<link href="/public/css/ddsmoothmenu.css" type="text/css" rel="stylesheet">
	
	<link rel="stylesheet" id="style-css" href="/public/css/style.css" type="text/css" media="all">
	<link rel="stylesheet" id="resnavc-css" href="/public/css/responsive-nav.css" type="text/css" media="all">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800|Oswald:400,300,700" rel="stylesheet" type="text/css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" src="/public/js/jquery.js"></script>

</head>
<body id="top" class="home">
	<div id="skrollr-body">
		@include('includes.menu')

		@yield('content')

		@include('includes.footer')

		<p id="back-top">
			<a href="#top"><img alt="" src="/public/images/scrollto_top.png"></a>
		</p>

		<!--contact us-->

		<script type="text/javascript" src="/public/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/public/js/jquery.localscroll-1.2.7-min.js"></script>
		<script type="text/javascript" src="/public/js/jquery.scrollTo-1.4.2-min.js"></script>
		<script type="text/javascript" src="/public/js/jquery.isotope.min.js"></script>
		<script type="text/javascript" src="/public/js/jquery.colorbox.js"></script>
		<script type="text/javascript" src="/public/js/jquery.backstretch.min.js"></script>
		<script type="text/javascript" src="/public/js/jbc.js"></script>
		<script type="text/javascript" src="/public/js/script.js"></script>

		<!--hieu ung text chuyen dong-->
		<script type="text/javascript" src="/public/js/skrollr.min.js"></script>
		<script type="text/javascript" src="/public/js/scrollr-init.js"></script>

		<!-- scroll page -->
		<script src="/public/js/jquery.easing.1.3.js"></script>
		<script src="/public/js/scrollify.min.js"></script>

		<script type="text/javascript">
			/* <![CDATA[ */
			var slides = ["/public/images/dgm.jpg"];
			/* ]]>*/
		</script>
		<script type="text/javascript">
			/* <![CDATA[ */
			var jt = ["/public/images/small.jpg"];
			/* ]]>*/
		</script>
		<script type="text/javascript" src="/public/js/backstretch-init.js"></script>
		<script>
		$(document).ready(function(){
		    //menu mobile
			$("#flip").click(function(){
		    $("#panel").slideToggle("milliseconds");});
		  
			// hide #back-top first
			$("#back-top").hide();
			
			// fade in #back-top
			$(function () {
				$(window).scroll(function () {
					if ($(this).scrollTop() > 100) {
						$('#back-top').fadeIn();
					} else {
						$('#back-top').fadeOut();
					}
				});

				// scroll body to 0px on click
				$('#back-top a').click(function () {
					$('body,html').animate({
						scrollTop: 0
					}, 800);
					return false;
				});
			});

		});
		</script>
		<script>
		$(document).ready(function () {
//		    $(document).on("scroll", onScroll);
//		    
//		    //smoothscroll
//		    $('a[href^="#"]').on('click', function (e) {
//		        e.preventDefault();
//		        $(document).off("scroll");
//		        /*
//		        $('a').each(function () {
//		            $(this).removeClass('active');
//		        })
//		        $(this).addClass('active');*/
//		      
//		        var target = this.hash,
//		            menu = target;
//		        $target = $(target);
//		        $('html, body').stop().animate({
//		            'scrollTop': $target.offset().top+2
//		        }, 500, 'swing', function () {
//		            window.location.hash = target;
//		            $(document).on("scroll", onScroll);
//		        });
//		    });
		});

			function onScroll(event){
				
				var scrollPos = $(document).scrollTop();
				$('.nav a').each(function () {
					var currLink = $(this);
					var refElement = $(currLink.attr("href"));
					if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
						$('.nav a').removeClass("active");
						currLink.addClass("active");
					}
					/*else{
						currLink.removeClass("active");
					}*/
				});
			}
		</script>
	</div>
</body>
</html>