 @extends('template') 
 @section('main')
 <main id="site-content" role="main" class="error-page">
  <div class="container">
    <div class="col-12 text-center">        
      <h1>Whoops!</h1>
      <h2>
        Sorry, we couldn't find the page you requested. (Error 404)
      </h2>
      <ul>
       @foreach($company_pages as $company_page)
       <li>
        <a href="{{ url($company_page->url) }}" class="link-contrast">
          {{ $company_page->name }}
        </a>
      </li>
      @endforeach
    </ul>
  </div>
</div>
</main>
@stop
