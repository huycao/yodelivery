<header class="clearfix " id="mobile-header">
	<div style="width: 100%; position: fixed; background: none repeat scroll 0% 0% rgb(255, 255, 255); z-index: 999;">
		<div class="click-nav" id="nav">
			<ul class="js">
				<li>
					<div class="sm">
						<a href="{{ URL::to(Route('HomePage')) }}">
							<img src="/public/images/sm.png"></a>
					</div>
					<div id="flip" style="float:right;">
						<a class="clicker">
							<div style="width:42px; margin:auto;">
								<img src="/public/images/menu.png"></div>
						</a>
					</div>
					<ul id="panel" class="wh" style="display: none;">
						<li>
							<a href="{{ URL::to(Route('HomePage')) }}" class="is_mob_onepage scroll-link @if($slugMenu == 'home') active @endif" id="">Home</a>
						</li>
						<!-- <li>
							<a href="#" class="is_mob_onepage scroll-link">Service</a>
						</li> -->
						<li>
							<a href="{{ URL::to(Route('PublicsherPage')) }}" class="is_mob_onepage scroll-link @if($slugMenu == 'publisher') active @endif">Publisher</a>
						</li>
						<li>
							<a href="{{ URL::to(Route('AdvertisersPage')) }}" class="is_mob_onepage scroll-link @if($slugMenu == 'advertiser') active @endif">Advertisers</a>
						</li>
						<li>
							<a href="{{ URL::to(Route('AboutUsPage')) }}" class="is_mob_onepage scroll-link @if($slugMenu == 'about-us') active @endif">About Us</a>
						</li>
						<li>
							<a href="{{ URL::to(Route('ContactUsPage')) }}" class="is_mob_onepage scroll-link @if($slugMenu == 'contact-us') active @endif">Contact Us</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</header>

<!-- Desktop Navigation-->
<nav id="navigation">
	<div class="menu_sl">
		<article>
			<a title="Live" href="{{ URL::to(Route('HomePage')) }}">
				<img class="logo pull-right" alt="dgm" title="dgm" src="/public/images/dgm1.png"></a>
		</article>
		<div class="nav">
			<div id="smoothmenu1" class="ddsmoothmenu">
				<ul>
					<li style="z-index: 2000;" class="mar-r20">
						<a class="menu_fa_text live @if($slugMenu == 'home') active @endif" href="{{ URL::to(Route('HomePage')) }}">HOME</a>
					</li>
					<!-- <li style="z-index: 1999;" class="mar-r20">
						<a class="menu_fa_text movie @if($slugMenu == 'home') active @endif" href="{{-- URL::to(Route('HomePage')) --}}">SERVICE</a>
					</li> -->
					<li style="z-index: 1998;" class="mar-r20">
						<a class="menu_fa_text drama @if($slugMenu == 'publisher') active @endif" href="{{ URL::to(Route('PublicsherPage')) }}">PUBLISHER</a>
					</li>
					<li style="z-index: 1997;" class="mar-r20">
						<a class="menu_fa_text show @if($slugMenu == 'advertiser') active @endif" href="{{ URL::to(Route('AdvertisersPage')) }}">Advertisers</a>
					</li>
					<li style="z-index: 2000;" class="mar-r20">
						<a class="menu_fa_text kids @if($slugMenu == 'about-us') active @endif" href="{{ URL::to(Route('AboutUsPage')) }}">About us</a>
					</li>
					<li style="z-index: 1999;" class="mar-r20">
						<a class="menu_fa_text documentary @if($slugMenu == 'contact-us') active @endif" href="{{ URL::to(Route('ContactUsPage')) }}">Contact Us</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>
<!--/Navigation-->