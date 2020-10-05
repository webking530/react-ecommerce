<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p>{{ Auth::guard('admin')->user()->first()->username }}</p>
				<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		</div>
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			<li class="{{ (Route::current()->uri() == 'admin/dashboard') ? 'active' : ''  }}"><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
			@if(Auth::guard('admin')->user()->can('manage-admin'))
				<li class="treeview {{ (Route::current()->uri() == 'admin/admin_users') ? 'active' : ''  }}">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Manage Admin</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="{{ (Route::current()->uri() == 'admin/admin_users') ? 'active' : ''  }}"><a href="{{ url('admin/admin_users') }}"><i class="fa fa-circle-o"></i><span>Admin Users</span></a></li>
						<li class="{{ (Route::current()->uri() == 'admin/roles') ? 'active' : ''  }}"><a href="{{ url('admin/roles') }}"><i class="fa fa-circle-o"></i><span>Roles & Permissions</span></a></li>
					</ul>
				</li>
			@endif
			@if(Auth::guard('admin')->user()->can('*-users'))
				<li class="{{ (Route::current()->uri() == 'admin/users' || Route::current()->uri() == 'admin/add_user' || Route::current()->uri() == 'admin/edit_user/{id}') ? 'active' : ''  }}"><a href="{{ url('admin/users') }}"><i class="fa fa-user"></i><span>Manage User</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-block_users'))
				<li class="{{ (Route::current()->uri() == 'admin/blocked_users') ? 'active' : ''  }}"><a href="{{ url('admin/blocked_users') }}"><i class="fa fa-ban"></i><span>Blocked Users</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-stores'))
				<li class="{{ (Route::current()->uri() == 'admin/stores') ? 'active' : ''  }}"><a href="{{ url('admin/stores') }}"><i class="fa fa-home"></i><span>Manage Stores</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-emails'))
				<li class="treeview {{ (Route::current()->uri() == 'admin/email_settings' || Route::current()->uri() == 'admin/send_email') ? 'active' : ''  }}">
					<a href="#">
						<i class="fa fa-envelope-o"></i> <span>Manage Emails</span><i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="{{ (Route::current()->uri() == 'admin/send_email') ? 'active' : ''  }}"><a href="{{ url('admin/send_email') }}"><i class="fa fa-circle-o"></i><span>Send Email</span></a></li>
						<li class="{{ (Route::current()->uri() == 'admin/email_settings') ? 'active' : ''  }}"><a href="{{ url('admin/email_settings') }}"><i class="fa fa-circle-o"></i><span>Email Settings</span></a></li>
					</ul>
				</li>
			@endif

			@if(Auth::guard('admin')->user()->can('manage-category'))
				<li class="{{ (Route::current()->uri() == 'admin/categories' || Route::current()->uri() == 'admin/add_category' || Route::current()->uri() == 'admin/edit_category/{id}') ? 'active' : ''  }}"><a href="{{ url('admin/categories') }}"><i class="fa fa-cubes"></i><span>Manage Categories</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('*-products'))
				<li class="{{ (Route::current()->uri() == 'admin/products') ? 'active' : ''  }}"><a href="{{ url('admin/products') }}"><i class="fa fa-tasks"></i><span>Manage Products</span></a></li>
			@endif

			@if(Auth::guard('admin')->user()->can('manage-orders'))
				<li class="{{ (Route::current()->uri() == 'admin/orders') ? 'active' : ''  }}"><a href="{{ url('admin/orders') }}"><i class="fa fa-shopping-cart"></i><span>Manage Orders</span></a></li>
			@endif

			@if(Auth::guard('admin')->user()->can('manage-coupon_code'))
				<li class="{{ (Route::current()->uri() == 'admin/coupon_code') ? 'active' : ''  }}"><a href="{{ url('admin/coupon_code') }}"><i class="fa fa-ticket"></i><span>Manage Coupon Code</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-fees'))
				<li class="{{ (Route::current()->uri() == 'admin/fees') ? 'active' : ''  }}"><a href="{{ url('admin/fees') }}"><i class="fa fa-dollar"></i><span>Manage fees</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-owe_amount'))
				<li class="{{ (Route::current()->uri() == 'admin/owe') ? 'active' : ''  }}"><a href="{{ url('admin/owe') }}"><i class="fa fa-money"></i><span>Manage Owe Amount</span></a></li>
			@endif			
			@if(Auth::guard('admin')->user()->can('manage-return_policy'))
				<li class="{{ (Route::current()->uri() == 'admin/returns_policy') ? 'active' : ''  }}"><a href="{{ url('admin/returns_policy') }}"><i class="fa fa-recycle"></i><span>Manage Returns Policy</span></a></li>
			@endif

			@if(Auth::guard('admin')->user()->can('manage-reports'))
				<li class="{{ (Route::current()->uri() == 'admin/reports') ? 'active' : ''  }}"><a href="{{ url('admin/reports') }}"><i class="fa fa-file-text-o"></i><span>Reports</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-product_reports'))
				<li class="{{ (Route::current()->uri() == 'admin/product_reports') ? 'active' : ''  }}"><a href="{{ url('admin/product_reports') }}"><i class="fa fa-pencil"></i><span>Product Reports</span></a></li>
			@endif
			
			
			@if(Auth::guard('admin')->user()->can('manage-language'))
				<li class="{{ (Route::current()->uri() == 'admin/language') ? 'active' : ''  }}"><a href="{{ url('admin/language') }}"><i class="fa fa-language"></i><span>Manage Language</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-country'))
				<li class="{{ (Route::current()->uri() == 'admin/country') ? 'active' : ''  }}"><a href="{{ url('admin/country') }}"><i class="fa fa-globe"></i><span>Manage Country</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-currency'))
				<li class="{{ (Route::current()->uri() == 'admin/currency') ? 'active' : ''  }}"><a href="{{ url('admin/currency') }}"><i class="fa fa-dollar"></i><span>Manage Currency</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-pages'))
				<li class="{{ (Route::current()->uri() == 'admin/pages') ? 'active' : ''  }}"><a href="{{ url('admin/pages') }}"><i class="fa fa-newspaper-o"></i><span>Manage Static Pages</span></a></li>
			@endif

			@if(Auth::guard('admin')->user()->can('manage-home_page_sliders'))
				<li class="{{ (Route::current()->uri() == ADMIN_URL.'/slider') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/slider') }}"><i class="fa fa-image"></i><span>Manage Home Page Sliders</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-our_favouritest'))
				<li class="{{ (Route::current()->uri() == ADMIN_URL.'/feature_slider') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/feature_slider') }}"><i class="fa fa-image"></i><span>Manage our Favourites</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-metas'))
				<li class="{{ (Route::current()->uri() == 'admin/metas') ? 'active' : ''  }}"><a href="{{ url('admin/metas') }}"><i class="fa fa-bar-chart"></i><span>Manage Metas</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-join_us'))
				<li class="{{ (Route::current()->uri() == ADMIN_URL.'/join_us') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/join_us') }}"><i class="fa fa-image"></i><span> Join Us </span></a></li>
			@endif
			
			@if(Auth::guard('admin')->user()->can('manage-api_credentials'))
				<li class="{{ (Route::current()->uri() == 'admin/api_credentials') ? 'active' : ''  }}"><a href="{{ url('admin/api_credentials') }}"><i class="fa fa-gear"></i><span>Api Credentials</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-payment_gateway'))
				<li class="{{ (Route::current()->uri() == 'admin/payment_gateway') ? 'active' : ''  }}"><a href="{{ url('admin/payment_gateway') }}"><i class="fa fa-paypal"></i><span>Payment Gateway</span></a></li>
			@endif
			@if(Auth::guard('admin')->user()->can('manage-site_settings'))
				<li class="{{ (Route::current()->uri() == 'admin/site_settings') ? 'active' : ''  }}"><a href="{{ url('admin/site_settings') }}"><i class="fa fa-cogs"></i><span>Site Settings</span></a></li>
			@endif
		</ul>
	</section>
</aside>