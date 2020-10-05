@include('common.head')
@if (!isset($exception))
	@if (!in_array(Route::current()->uri(), ['signup','login', 'forgot'])) 
	   	@include('common.header')
	@endif
@endif

@yield('main')

@include('common.foot')

@if (!isset($exception))
	@if (!in_array(Route::current()->uri(), ['cart','messages','activity','shop/{page?}/{category?}']))
		@include('common.footer')
	@endif
@endif