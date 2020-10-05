@extends('template') 
@section('main')    
<main id="site-content" role="main" class="error-page">      
  <div class="container">
    <div class="col-12 text-center">    
      <h1>
        Token Mismatch!
      </h1>
      <h2>
        Please Login Again. (Error 500)
      </h2>
      <ul>
        <li>
          <a href="{{ url('login') }}">
            Login
          </a>
        </li>
        @foreach($company_pages as $company_page)
        <li>
          <a href="{{ url($company_page->url) }}">
            {{ $company_page->name }}
          </a>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</main>
@stop