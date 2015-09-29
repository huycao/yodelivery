@extends('layout.staticHome')
@section('content')
<div id="pagewrap">
	<section id="home" class="panel pagehome home-scroll" data-section-name="pagehome">
		<div class="container-fluid ">
			<div class="row-fluid">
				<div class="container mact inner" style="width:100%;margin-top:0px !important;height:720px !important">
					<div class="row page" data-live-color="rgba(0,0,0,0.8)" align="center" style="width:100%;">
						<div class="sl_title_home">
							<article align="center" data-100-top="transform: translate3d(0px, 0px, 0px) scale(0.800177283709563) rotate(0deg); opacity: 1;"data-250-top="transform: translate3d(0px, 0px, 0px) scale(0.999999352992659) rotate(0deg); opacity: 4.99999352992659; display: block;">
								<h3>
									<span class="font_1">YoMedia</span>
									Reaches
									<span class="font_2">98%</span>
									of the Web
									<br>
									and Draws
									<span class="font_2">Over 10 Million Users</span>
									every Month
								</h3>
							</article>
							<article align="center" data-100-top="transform: translate3d(0px, 0px, 0px) scale(0.800177283709563) rotate(0deg); opacity: 0;"data-250-top="transform: translate3d(0px, 0px, 0px) scale(0.999999352992659) rotate(0deg); opacity: 4.99999352992659; display: block;">
								<div id="unpin" class="info_title_home">
									We aim to connect quality Advertisers with relevant Publishers  and Consumers. We've joined together with premium Publishers, built the comprehensive ad network, and forged partnerships with the best in the industry because we want top - notch advertisers to get the results that they.
								</div>
							</article>
						</div>
					</div>
				</div>
			</div>
			<div class="row"></div>
		</div>
	</section>
	<!--service -->
	<section id="service" data-live-color="#f7f7f7" style="background:url(/public/images/img_1.png) rgb(247, 247, 247);" 
    class="panel pageservice band page pagesection-parallax skrollable rendered home-scroll" data-300="background-position:0px 0px;" 
	data-end="background-position:0px -8000px;" data-section-name="pageservice">
		<div class="container-fluid inner">
			<div class="row-fluid">
				<div class="container inner">
					<div class="welcome row" style="padding-bottom:0;">
						<article data-350="transform:rotateX(360deg);" data-650="transform:rotateX(0deg);">
							<h1 class="title_youmedia">Yomedia</h1>
							<div class="sl_info_yomedia">
								Works on proprietary technology platform that combines the latest ad serving technologies with advanced web analytics to maximize your advertising revenue. 
					It is highly scalable and reliable device independent ad serving technology
							</div>
						</article>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- end service -->

	<!-- publisher -->
	<section id="publisher" data-live-color="rgba(0,0,0,0.8)" style=""
	 class="panel pagepublisher band page pagesection-parallax skrollable  unrendered " data-4800="background-position:0px 0px;" 
	 data-end="background-position:0px -8000px;" data-section-name="pagepublisher">
		<div class="container-fluid inner">
			<div class="row-fluid">
				<div class="container inner">
					<div class="welcome row" style="padding-bottom:0;">
						<article data-1100="margin-left:-100px;" data-1400="margin-left:0px;">
							<div class="service_group1">
								<ul>
									<li>
										<a href="{{ URL::to(route('VideoPage')) }}">
											<img alt="video" src="/public/images/icon_details_video.png">
											<h3>Video</h3>
										</a>
									</li>
									<li>
										<a href="{{ URL::to(route('AboutUsPage')) }}">
											<img alt="video" src="/public/images/icon_display.png">
											<h3>DISPLAY</h3>
										</a>
									</li>
								</ul>
							</div>
						</article>
						<article data-1100="margin-left:-100px;" data-1400="margin-left:0px;">
							<div class="service_group2">
								<div class="left1">
									<div class="title_gr">What We Deliver</div>
									<ul class="margin_left">
										<li>
											<a href="#">Higher click-through rates</a>
										</li>
										<li>
											<a href="#">Higher brand recognition</a>
										</li>
										<li>
											<a href="#">Higher conversion rates</a>
										</li>
										<div class="clearfix"></div>
									</ul>
								</div>
								<div class="left2">
									<div class="title_gr">How We Deliver</div>
									<ul class="margin_left">
										<li>
											<a href="#">Behavioral targeting</a>
										</li>
										<li>
											<a href="#">Vertical channel targeting</a>
										</li>
										<li>
											<a href="#">Geo-targeting</a>
										</li>
										<li>
											<a href="#">And more!</a>
										</li>
										<div class="clearfix"></div>
									</ul>
								</div>
							</div>
						</article>
					</div>
					<!--/ row-->
				</div>
			</div>
		</div>
	</section>
	<!-- End publisher -->

	<!-- advertisers -->
	<section id="advertisers" data-live-color="rgba(0,0,0,0.8)" 
	class="panel pageadvertisers band page pagesection-parallax skrollable  unrendered " data-4800="background-position:0px 0px;" 
	data-end="background-position:0px -8000px;" data-section-name="pageadvertisers">
		<div class="container-fluid inner">
			<div class="row-fluid">
				<div class="container inner" >
					<div class="welcome row" style="padding-bottom:0;">
						<div class="group_media">
							<article data-1900="opacity: 0;" data-2300="opacity: 2;">
								<div class="left_media">
									<a href="publisher.html">
										<img alt="video" src="/public/images/img_publisher.png"></a>
								</div>
							</article>
							<article data-1900="opacity: 0;" data-2300="opacity: 2;">
								<div class="right_media">
									<a href="advertiser.html">
										<img alt="video" src="/public/images/advertiser.png"></a>
								</div>
							</article>
							<article data-1900="opacity: 0;" data-2300="opacity: 2;">
								<div class="bottom_media">
									<a href="#">
										<img alt="video" src="/public/images/yomedia.png"></a>
								</div>
							</article>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- end advertisers-->

	<!-- WHY YOMEDIA -->
	<section id="aboutus" data-live-color="rgba(0,0,0,0.8)" style="background: #eaf7f6; " 
	class="panel pageaboutus band page pagesection-parallax skrollable  unrendered " data-4800="background-position:0px 0px;" 
	data-end="background-position:0px -8000px;" data-section-name="pageaboutus">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="container inner">
					<div class="welcome row">

						<h1 class="title_service">Contact Us</h1>
						<article>
							<div class="map">
								<div id="map_canvas" style="width:485px; height: 322px"></div>
								<div class="clearfix"></div>
							</div>
						</article>
						<article >
							<div class="address_contacus">
								<p>
									Headquarter:
									<span style="comfortaa_bold">28 Phung Khac Khoan St., Dakao Ward, Dist 1, HCMC</span>
								</p>
								<p>
									Branch Office:
									<span style="comfortaa_bold">28 An Khang Apt., An Phu Ward, Dist 2, HCMC</span>
								</p>
								<p>
									- Tell:
									<span style="comfortaa_bold">(08) 6281 7605 - Fax: (08) 6281 7605</span>
								</p>
								<p>
									- Email:
									<span style="comfortaa_bold">infoyomedia.vn</span>
								</p>
								<p>
									- Website:
									<span style="comfortaa_bold">www.yomedia.vn</span>
								</p>
							</div>
						</article>

					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</section>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script>
var map;
var myCenter=new google.maps.LatLng(10.797709, 106.739869);
var contentString = "<table><tr><th>Yomedia</th></tr><tr><td>Address: Block C, An Khang Tower, An Phu Ward, District 2, HoChiMinh City, Vietnam</td></tr></table>";
var marker=new google.maps.Marker({
    position:myCenter,
    map: map,
    title: 'Pinetech'
});



function initialize() {
  var mapProp = {
      center:myCenter,
      zoom: 14,
      // draggable: false,
      // scrollwheel: false,
      mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  
  map = new google.maps.Map(document.getElementById("map_canvas"), mapProp);
  marker.setMap(map);
    
  google.maps.event.addListener(marker, 'click', function() {
  var infowindow = new google.maps.InfoWindow({
      content: contentString
  });
  infowindow.setContent(contentString);
  infowindow.open(map, marker);
    
  }); 
};

initialize();


</script>
@stop