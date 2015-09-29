@extends('layout.main')
@section('content')
<!--header end -->
<div class="slcontent" >
	<div class="bg_advertiser">
		<div class="info_textadvertiser">

			<p>
				With 98% of the web within our reach, you can be sure you'll
				<br/>
				be able to grab your customers wherever they are. Whether they're surfing other websites or on the go with their mobile devices,
				<br/>
				YoMedia has the ability to bring your brand in front of users.
			</p>
			<div class="clearfix"></div>
			<div class="btn_contactus">
				<a href="{{ URL::to(Route('ContactUsPage')) }}">
					<img alt="" src="/public/images/btn_contactus.png"></a>
			</div>
		</div>
	</div>
	
</div>
@stop