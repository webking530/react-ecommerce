@extends('template')
@section('main')
<main id="site-content" role="main">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 col-12 cls_disableacc text-center">
        <div id="account_recovery_panel" class="security-check-panel panel text-center">
          <div class="panel-body">
            <!-- <div class="icon-circle">
              <i class="dash-icon dash-profile"></i>
            </div> -->
            <h3>
              Account Disabled
            </h3>
            <p>
              Disabled your account. Please email us to continue.
            </p>
            <form action="mailto:{{$admin_email}}" method="GET">
              <button class="search-button form-inline btn btn-primary btn-large">
                Email Us
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@stop