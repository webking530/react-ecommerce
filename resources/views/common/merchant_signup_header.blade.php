<div id="merchant-header">
    <div class="container">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 d-none d-lg-flex">
            <div class="col-lg-8 col-sm-9 col-xs-8 col-md-9 cls_merchantheader">
                <h1 class=""><a href="{{ url('/') }}">
					<span class="merchantlogo d-flex align-items-center">
						<img src='{{ $favicon }}'>
						<span class="chepter">
						{{ trans('messages.merchant.merchant') }} 
						</span>
					</span> 
					
				</a>
			</h1>
            </div>
            <div class="col-12 col-md-3 col-lg-4">
                <ul class="head-list"></ul>
            </div>
        </div>

        <div class="show-sm d-lg-none d-block">
            <div class="text-center logo-show mer_head">
                <a class="" href="#">
                    <h1>
					<a href="{{ url('/') }}">
						<span class="merchantlogo">
							<img src='{{ url("image/logo_blu_tra.png")}}' />
							<span class="chepter">{{ trans('messages.merchant.merchant') }} </span>
						</span> 
						
					</a>
				</h1>
                    <i class="nav-bar fa fa-bars" aria-hidden="true"></i>
                </a>
            </div>
            <div class="show-list">
                <div class="col-lg-8 col-sm-12 col-xs-12 col-md-9 pad-tab">

                    <ul class="gnb">
                        <li class="dashboard"><a href="{{ url('merchant/dashboard') }}" class="current">Dashboard</a></li>
                        <li class="products"><a href="{{ url('merchant/all_products') }}">Products</a>
                            <small>
								<a href="{{ url('merchant/all_products') }}">All Products</a>
								<a href="#">Add Product</a>
								<a href="#">Import</a>
								<a href="#">Collections</a>

								<a href="#">Organize</a>

							</small>
                        </li>
                        <li class="orders"><a href="#">Orders

						</a>
                            <small>
							<a href="#">Order Management</a>
							<a href="#">Customers</a>

						</small>
                        </li>
                        <li class="insights"><a href="#">Insights</a></li>
                        <li class="promote"><a href="#">Promote</a>
                            <small>
							<a href="#">Coupons</a>

							<a href="#" target="_blank">{{$site_name}} Anywhere</a>
						</small>
                        </li>
                        <li class="campaigns"><a href="#">Campaigns</a>
                            <small>
							<a href="#">Your Campaigns</a>
							<a href="#">Create Campaign</a>
						</small>
                        </li>
                        <li class="settings"><a href="#">Settings</a>
                            <small>
							<a href="#">The Basics</a>
							<a href="#">Brand Image</a>
							<a href="#">Shipping Rules</a>
							<a href="#">Policies</a>
							<a href="#">Getting Paid</a>
							<a href="#">Storefront</a>
							<a href="#">Notifications</a>
						</small>
                        </li>
                        <li class="faq"><a href="#" target="_blank">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 flt-right pad-tab2">
                    <ul class="head-list flt-right">

                        <li>
                            <a href="{{ url('activity') }}" class="dash-icon dash-arrow"></a>
                            <div class="msg-popup2">
                                <div class="popular-head">
                                    <h3>Store Activity</h3>
                                </div>
                                <div class="activity-content">
                                    <div class="msg-content bor-top">
                                        <a href="#" class="dash-icon dash-arrow" id="dash-arrow-ash" style="border-radius:100%"></a>
                                        <p><b>No Store Activity</b> Incoming activity for your store will be shown here.</p>
                                    </div>

                                </div>

                            </div>
                        </li>

                        <li>
                            <a href="{{ url('messages') }}" class="dash-car dash-icon"></a>

                        </li>

                        <li><a style="height: 22px;" href="{{ url('merchant/dashboard') }}" class="dash-icon dash-merchant">Store Name</a>
                            <div class="msg-popup5">
                                <ul class="msg-profile">
                                    <li>
                                        <a href="{{ url('profile') }}"><img src="../image/profile.png" width="30px" height="30px" style="border-radius:2px;" class="flt-left">
                                            <p class="flt-left mar-0 bold" style="padding:0px 10px">Store Name</p>
                                            <br>
                                            <p class="flt-left mar-0" style="padding:0px 10px"> View your store</p>
                                        </a>
                                    </li>

                                    <ul class="msg-profile-inner flt-left bor-bot-ash" style="padding:10px 0px;">
                                        <li><a href="{{ url('merchant/dashboard') }}">Dashboard</a></li>
                                        <li><a href="#">Orders</a></li>
                                        <li><a href="{{ url('merchant/all_products') }}">Products</a></li>
                                        <li><a href="#">Storefront</a></li>
                                        <!--<li><a href="#">Campaigns</a></li>-->
                                    </ul>
                                    <ul class="msg-profile-inner flt-left" style="padding:10px 0px;">
                                        <!--<li><a href="#">Download Seller App</a></li>
			<li><a href="#">Help Center</a></li>-->
                                        <li><a href="#">Store Settings</a></li>
                                    </ul>
                                </ul>

                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    @media (min-width: 1350px) {
        .container {
            padding: 0px 40px !important;
        }
    }
    
    .dash-icon::before {
        background-image: url("../image/merchant-logo.png");
    }
    
    #dash-arrow-ash:before {
        width: 12px;
        height: 16px !important;
        background-position: -40px -20px !important;
        background-size: 180px 50px !important;
        position: relative;
        top: -3px !important;
        content: '';
        display: inline-block;
        vertical-align: middle;
        margin: -3px 0 0;
        opacity: 0.55 !important;
        background-image: url("../image/header-icon.png") !important;
        left: -5px;
    }
    
    #dash-arrow-ash {
        border-radius: 100%;
        width: 25px;
        margin: 0px auto;
        height: 34px;
        border: 1px solid #ddd;
        padding: 19px !important;
        margin-bottom: 20px;
    }
    
    .dash-arrow::before {
        background-size: 150px 150px;
        background-position: -35px -2px !important;
        width: 12px;
        height: 22px !important;
        position: relative;
        top: 7px !important;
    }
    
    .dash-car:before {
        background-position: -66px -2px !important;
        background-size: 150px 150px;
        top: 3px !important;
    }
    
    .dash-merchant:before {
        background-position: -103px -2px !important;
        background-size: 150px 150px;
        top: -1px !important;
        margin-right: 10px !important;
    }
    
    .dash-merchant {
        position: relative;
        top: 4px;
    }
    
    .msg-content {
        text-align: center;
        padding-top: 80px;
    }
    
    .msg-profile li a {
        padding: 10px !important;
    }
    
    .dash-merchant::after {
        content: '';
        display: inline-block;
        width: 0;
        height: 0;
        border: 4px solid transparent;
        background: none;
        border-top-color: #878993;
        margin: 3px 0 0 8px;
        position: relative;
        top: 2px;
    }
    
    .head-list li {
        padding: 0px !important;
    }
    
    .head-list {
        overflow: inherit !important;
        padding-top: 13px;
        padding-bottom: 13px;
    }
    
    .activity-content p {
        padding: 0 70px;
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        font-size: 12px;
        line-height: 17px;
        color: #8a8f9c;
    }
    
    .activity-content b {
        display: block;
        padding-bottom: 9px;
        color: #383d48;
        font-size: 13px;
    }
    
    @media (max-width: 760px) {
        .nav-bar {
            margin: 15px 10px;
            border: 1px solid #ddd !important;
            color: #ddd !important;
        }
        .flt-right {
            float: left !important;
        }
        #container-wrapper {
            padding-top: 67px;
        }
        .merchant_signup h2 {
            padding-bottom: 10px;
            font-size: 24px;
            line-height: 32px;
        }
        .merchant_signup .frm .btn-area {
            text-align: left;
        }
        .merchant_signup .step {
            padding: 0 10px;
        }
    }
    
    @media(min-width: 760px) and (max-width: 1000px) {
        .head-list {
            padding-top: 0px !important;
        }
        #merchant-header .head-list {
            padding-top: 13px !important;
        }
        .msg-popup,
        .msg-popup2,
        .msg-popup5 {
            top: 24px !important;
        }
    }
</style>