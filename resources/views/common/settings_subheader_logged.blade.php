<div class="col-lg-3 min-size-short subset_head msidebar p-0">
    <div class="back-white set_left subset_paid">
        <ul class="set_menu">
            <li><a href="{{ url('merchant/settings') }}" aria-selected="{{(Route::current()->uri() == 'merchant/settings') ? 'true' : 'false'}}" class="current"><i class="icon ic-user"></i>{{ trans('messages.header.basics') }}</a></li>
            <li><a href="{{ url('merchant/settings_general') }}" aria-selected="{{(Route::current()->uri() == 'merchant/settings_general') ? 'true' : 'false'}}" class="current"><i class="icon ic-brand"></i>{{ trans('messages.header.brand_image') }}</a></li>
            
            <li><a href="{{ url('merchant/settings_paid') }}" aria-selected="{{(Route::current()->uri() == 'merchant/settings_paid') ? 'true' : 'false'}}" class="current"><i class="icon ic-credit"></i>{{ trans('messages.header.getting_paid') }}</a></li>

        </ul>
    </div>
</div>
