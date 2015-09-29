@extends('layout.main')
@section('content')

	<div class="slcontent">
		<div class="bg_publisher">
			<div class="info_textpublisher">

				<p>
					Get the most out of your website with the help of YoMedia. You've already filled your site with interesting content,
					<span style="color:#ff001e">now it's time to make money out of it</span>
					. We'll display relevant and targeted ads on your site and you'll earn revenue every time your visitors click on the ads.

reserve.
				</p>
				<div class="clearfix"></div>
				<div class="btn_contactus">
					<a href="{{ URL::to(Route('ContactInfoPage')) }}">
						<img alt="" src="/public/images/apply-now.png"></a>
				</div>
			</div>
		</div>

@stop		