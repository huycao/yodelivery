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

	<link rel="stylesheet" href="/public/css/style.css" type="text/css" media="all">
	<link rel="stylesheet" id="resnavc-css" href="/public/css/responsive-nav.css" type="text/css" media="all">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800|Oswald:400,300,700" rel="stylesheet" type="text/css">

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</head>

<body id="top" class="advertiser"> 
	<div id="skrollr-body">
		@include('includes.menu')

		@yield('content')

		@include('includes.footer')
		
	</div>

<script>
$(document).ready(function(){
    //menu mobile
	$("#flip").click(function(){
	    $("#panel").slideToggle("milliseconds");});
	});
</script>
</body>
</html>