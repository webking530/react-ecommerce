@include('common.head')
@include('common.header')

@if (Route::current()->uri() == 'change_password' || Route::current()->uri() == 'purchases' || Route::current()->uri() == 'edit_profile' || Route::current()->uri() == 'purchases/{id?}')
			@include('common.settings_subheader')
@endif	

@yield('main')

@include('common.foot')