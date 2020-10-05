<main class="cls_homemain">
	<div class="container cls_editprofile py-3 set_area">
		<div class="d-flex row p-0 ">
			<div class="col-lg-3 min-size-short subset_head msidebar pr-0">
				<div class="back-white set_left subset_paid">
				    <ul class="set_menu">
				        <li><a href="{{ url('edit_profile') }}" aria-selected="{{(Route::current()->uri() == 'edit_profile') ? 'true' : 'false'}}" class="current"><i class="ic-user"></i>{{ trans('messages.profile.edit_profile') }}</a></li>
				       	@if(empty(Auth::user()->google_id) && empty(Auth::user()->twitter_id) && empty(Auth::user()->fb_id))
				        <li><a href="{{ url('change_password') }}" aria-selected="{{(Route::current()->uri() == 'change_password') ? 'true' : 'false'}}" class="current"><i class="ic-pw"></i>{{ trans('messages.profile.password') }}</a></li>
				      	@endif
				        <li><a href="{{ url('purchases') }}" aria-selected="{{(Route::current()->uri() == 'purchases') ? 'true' : 'false'}}" class="current"><i class="ic-pur"></i>{{ trans('messages.header.orders') }}</a></li>
				    </ul>
			    </div>
			</div>
