@extends('layout.main')
@section('content')

<div class="slcontent" >
	<div class="container-fluid" style="margin-top:110px;min-height:400px">
		<div class="row-fluid">
			<div class="container inner">
				<div class="welcome row">
					<article>
						<!-- <h1 class="title_service">Contact Us</h1> -->
						<div class="map">
							<div id="map_canvas" style="width:485px; height: 322px"></div>
							<div class="clearfix"></div>
						</div>
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
			</div>
		</div>
	</div>
</div>
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