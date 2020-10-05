@extends('settings_template')
@section('main')

<div class="userset">


<ul class="set_menu">
<li><a href="{{ url('edit_profile') }}" aria-selected="{{(Route::current()->uri() == 'edit_profile') ? 'true' : 'false'}}" class="current"><i class="ic-user"></i>{{ trans('messages.profile.edit_profile') }}</a></li>

<li><a href="{{ url('change_password') }}" aria-selected="{{(Route::current()->uri() == 'change_password') ? 'true' : 'false'}}" class="current"><i class="ic-pw"></i>{{ trans('messages.profile.password') }}</a></li>
<li><a href="{{ url('purchases') }}" aria-selected="{{(Route::current()->uri() == 'purchases') ? 'true' : 'false'}}" class="current"><i class="ic-pur"></i>Orders</a></li>
<li><a href="{{ url('logout') }}" aria-selected="{{(Route::current()->uri() == 'purchases') ? 'true' : 'false'}}" class="current"><i class="ic-log"></i>Logout</a></li>

</ul>

</div>


</main>
@stop