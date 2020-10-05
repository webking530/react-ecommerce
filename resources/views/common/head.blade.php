<!DOCTYPE html>
<html lang="en-IN" xmlns:fb="http://ogp.me/ns/fb#" prefix="og: http://ogp.me/ns#">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="mobile-web-app-capable" content="yes">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta property="og:type" content="article" />
		<meta name="twitter:widgets:csp" content="on">
		@if (!isset($exception))
			@if(Route::current()->uri() == 'things/{id}')
				<title>{{ @$result->title }}</title>
				<meta name="description" content="{{ strip_tags(@$result->description) }}">
				<meta name="twitter:widgets:csp" content="on">
				<meta property="og:url" content="{{ url('/') }}/things/{{ @$result->id }}">
				<meta property="og:type" content="website" />
				<meta property="og:title" content="{{ @$result->title }}">
				<meta property="og:image" content="{{ @$result->image_name }}">
				<meta property="og:description" content="{{ strip_tags(@$result->description) }}">
				<meta property="og:image:alt" content="{{ @$result->image_name }}">
				<meta itemprop="image" src="{{ @$result->image_name }}">
				<link rel="image_src" href="#" src="{{ @$result->image_name }}">
				<meta name="twitter:title" content="{{ @$result->title }}">
				<meta name="twitter:site" content="{{ SITE_NAME }}">
				<meta name="twitter:url" content="{{ url('/') }}/things/{{ @$result->id }}">
			@elseif(Route::current()->uri() == 'profile/{uname?}')
				<title> {{ @$user->original_full_name }} </title>
				<meta name="description" content="{{@$user->original_full_name}}">
			@else
			<title>{{ $title ?? Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'title') }}</title>
				<meta property="og:title" content="{{ $title ?? Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'title') }}" />
				<meta property="og:description" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'description') }}">
				<meta name="description" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'description') }}">
				<meta name="keywords" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'keywords') }}">
			@endif
		@endif
		<link rel="search" type="application/opensearchdescription+xml" href="#" title="">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
		<link rel="shortcut icon" href="{{ $favicon }}" type="image/png">

		{!! Html::style('css/common.css?v='.$site_version) !!}
		{!! Html::style('css/daterangepicker.css?v='.$site_version) !!}
	</head>
	@php
		$body_class = " ";
		if(!isset($exception) && Route::current()->uri() == '/') {
			$body_class .= "cls_homepage ";
		}
		if(!isset($exception) && Route::current()->uri() == 'Route::current()->uri()') {
			$body_class .= "no_scroll ";
		}
	@endphp
	<body ng-app="App" class="{{ $body_class }}" ng-controller="appController">
	<div class="se-pre-con"></div>