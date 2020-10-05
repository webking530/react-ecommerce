@extends('template')
<title>{{ @$title }}</title>
@section('main')
<main id="site-content" role="main">
	<div class="container">   
		<div class="col-lg-12" style="background-color: #fff;">
			{!! $content !!}
		</div>
	</div>
</main>

@push('scripts')
<script type="text/javascript">
	$( document ).ready(function() { 
		var base_url = '{!! url('/') !!}';
		var user_token = '{!! Session::get('get_token') !!}';
		if(user_token!='') {
			$('a[href*="'+base_url+'"]').attr('href' , 'javascript:void(0)'); 
		}
	});
</script>
@endpush
@stop