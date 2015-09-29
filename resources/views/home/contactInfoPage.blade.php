@extends('layout.main')
@section('content')
<div class="slcontent" >
	<div class="sl_cnt" style="margin-top:100px;">
		<section  class="wap_publisher" data-live-color="rgba(0,0,0,0.8)" style="background: #e6e6e6">
			
			<form id="registerForm" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
				<div class="container-fluid">
					<div class="row-fluid">
						<div class="container inner">
							<div class="welcome row">
								<article>
									<h1 class="title_publisher">Contact Infomation</h1>
									@if( Session::has('success') )
									<div class="alert alert-success alert-dismissible" role="alert">
									  {{Session::get('success')}}
									</div>
									@endif
									<div class="form_left_contactus">
										<div class="row">
											<div class="row_left">
												First Name : <b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													<input type="text" name="first_name" id="first_name" value="{{{ Input::old('first_name')}}}" class="form-control input-lg"  placeholder="First Name">
													@if(isset($validate) && $validate->has('first_name'))
													<br/><span style="color:red;">{{ $validate->first('first_name') }}</span>
													@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												Last Name : <b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													<input type="text" name="last_name" id="last_name" value="{{{ Input::old('last_name')}}}" class="form-control input-lg"  placeholder="Last Name">
													@if(isset($validate) && $validate->has('last_name'))
													<br/><span style="color:red;">{{ $validate->first('last_name') }}</span>
													@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">Title :</div>
											<div class="row_right">
												<select class="selectpicker" id="title" name="title">
													<option value="" selected="selected">Select</option>
													<option value="Mr" @if('Mr' == Input::old('title'))selected="selected"@endif>Mr</option>
													<option value="Mrs" @if('Mrs' == Input::old('title'))selected="selected"@endif>Mrs</option>
													<option value="Ms" @if('Ms' == Input::old('title'))selected="selected"@endif>Ms</option>
												</select>
												
					                            @if(isset($validate) && $validate->has('title'))
												<br/><span style="color:red;">{{ $validate->first('title') }}</span>
												@endif
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												Company :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													
													<input type="text" name="company" class="form-control input-lg" id="company" value="{{{ Input::old('company') }}}" placeholder="Company">
													@if(isset($validate) && $validate->has('company'))
												<br/><span style="color:red;">{{ $validate->first('company') }}</span>
												@endif
												</div>

											</div>
										</div>
										<div class="row">
											<label>
												(If you do not have a company name, Please fill your full name. We will issue you payment based on one of these two detail)
											</label>
										</div>
										<div class="row">
											<div class="row_left" style="margin-top:16px">
												Address:
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													
													<textarea name="address" id="address" class="" rows="4" cols="50" placeholder="Address">{{{ Input::old('address')}}}</textarea>
													@if(isset($validate) && $validate->has('address'))
												<br/><span style="color:red;">{{ $validate->first('address') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												City :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													
													<input type="text" name="city" class="form-control input-lg" id="city" value="{{{ Input::old('city')}}}" placeholder="City">
													@if(isset($validate) && $validate->has('city'))
												<br/><span style="color:red;">{{ $validate->first('city') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												State :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													
													<input type="text" name="state" class="form-control input-lg" id="state" value="{{{ Input::old('state') }}}" placeholder="State">
													@if(isset($validate) && $validate->has('state'))
												<br/><span style="color:red;">{{ $validate->first('state') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												Postcode :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													
													<input type="text" name="postcode" class="form-control input-lg" id="postcode" value="{{{ Input::old('postcode') }}}" placeholder="Postcode">
													@if(isset($validate) && $validate->has('postcode'))
												<br/><span style="color:red;">{{ $validate->first('postcode') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												Country :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													<select class="selectpicker" id="country" name="country">
														@foreach($listCountry as $id=>$name)
														<option value="{{$id}}" @if($id == Input::old('country'))selected="selected"@endif>{{$name}}</option>
														@endforeach
													</select>
													
						                            @if(isset($validate) && $validate->has('country'))
												<br/><span style="color:red;">{{ $validate->first('country') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												Payment to :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													
													<input type="text" name="payment_to" class="form-control input-lg" id="payment_to" value="{{{ Input::old('payment_to') }}}" placeholder="Payment to">
													@if(isset($validate) && $validate->has('payment_to'))
												<br/><span style="color:red;">{{ $validate->first('payment_to') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<label>
												Please fill in your name on your bank account, we will issue your payment to this account.
											</label>
										</div>
									</div>
									<div class="form_right_contactus">
										<div class="row">
											<div class="row_left">
												Phone :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">													
													<input type="text" name="phone" class="form-control input-lg" id="phone" value="{{{ Input::old('phone') }}}" placeholder="Phone">
													@if(isset($validate) && $validate->has('phone'))
												<br/><span style="color:red;">{{ $validate->first('phone') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												Fax :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">													
													<input type="text" name="fax" class="form-control input-lg" id="fax" value="{{{ Input::old('fax') }}}" placeholder="Fax">
													@if(isset($validate) && $validate->has('fax'))
												<br/><span style="color:red;">{{ $validate->first('fax') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												Email :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">													
													<input type="text" name="email" class="form-control input-lg" id="email" value="{{{ Input::old('email') }}}" placeholder="Email">
													@if(isset($validate) && $validate->has('email'))
												<br/><span style="color:red;">{{ $validate->first('email') }}</span>
												@endif
												</div>
											</div>
										</div>
									</div>
								</article>
							</div>
							<div class="welcome row">
								<article>
									<h1 class="title_publisher">Website Infomation</h1>
									<div class="form_left_contactus">
										<div class="row">
											<div class="row_left">
												Site Name :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													<input type="text" name="site_name" id="site_name" value="{{{ Input::old('site_name') }}}" class="form-control input-lg"  placeholder="Site Name">													
													@if(isset($validate) && $validate->has('site_name'))
												<br/><span style="color:red;">{{ $validate->first('site_name') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<label>(Only alpha-numeric accepted for site name)</label>
										</div>
										<div class="row">
											<div class="row_left">
												Site URL :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">
													<input type="text" name="site_url" id="site_url" value="{{{ Input::old('site_url') }}}" class="form-control input-lg"  placeholder="Site Url">													
													@if(isset($validate) && $validate->has('site_url'))
												<br/><span style="color:red;">{{ $validate->first('site_url') }}</span>
												@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="row_left">
												Site Description :
												<b style="color:red;">*</b>
											</div>
											<div class="row_right">
												<div class="form-group">													
													<textarea name="site_description" id="site_description" class="" rows="4" cols="50" placeholder="Site Description">{{{ Input::old('site_description') }}}</textarea>
													@if(isset($validate) && $validate->has('site_description'))
												<br/><span style="color:red;">{{ $validate->first('site_description') }}</span>
												@endif
												</div>
											</div>
										</div>
									</div>
									<div class="form_right_contactus2">
										<div class="checkbox">
											<label>
												<input name="agree" @if(Input::old('agree')) checked="" @endif type="checkbox">
												I have read and accept the terms and conditions of the Web Publisher Agreement
												@if(isset($validate) && $validate->has('agree'))
												<br/><span style="color:red;">{{ $validate->first('agree') }}</span>
												@endif
											</label>
										</div>

									<div class="clearfix"></div>
									<div class="group_button">
										<span>* Characters are case sensitive</span>
										<div class="sl_button">
											<ul>
												<li>
													<button type="reset">Reset</button>
												</li>
												<li>
													<button type="submit">Submit</button>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</article>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>

</div>