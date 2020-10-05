@extends('merchant_template') @section('main')
<?php
$no_product_url=url('/image/profile.png');
?>
    <div class="cls_dashmain">
        <input type="hidden" id="token" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="container container-pad" ng-controller="products" ng-cloak>
            <input type="hidden" id="pagin_next" value="{{ trans('messages.pagination.pagi_next') }} ">
            <input type="hidden" id="pagin_prev" value="{{ trans('messages.pagination.pagi_prev') }} ">

            <div class="cls_allproduct pt-4">
                <div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item">{{ trans('messages.products.products') }}</li>
                      </ol>
                    </div>
                    <a href="{{ url('merchant/add_product') }}" class="btn-add">{{ trans('messages.products.add_product') }}</a>
                </div>
            
            <div class="cls_merchentwrapper new-listing">
                <input type="hidden" id="current" value="all">
                <ul class="cls_mtab tab3">
                    <li>
                        <a href="javascript:void(0);" data="all" name="all" class="all current">
                            {{ trans('messages.products.all_products') }}           
                                <small ng-if=" count_all > 0">@{{ count_all }}</small>            
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data="active" name="active" class="active ">
                            {{ trans('messages.products.active') }} 
                            <small ng-if="count_active > 0">@{{ count_active }}</small>            
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data="inactive" name="pending" class="inactive">
                            {{ trans('messages.products.inactive') }} 
                            <small ng-if="count_inactive > 0">@{{ count_inactive }}</small>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data="soldout" name="sold_out" class="soldout">
                            {{ trans('messages.products.soldout') }} 
                            <small ng-if="count_soldout > 0">@{{ count_soldout }}</small>            
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data="awaiting" name="awaiting" class="awaiting">
                           {{ trans('messages.products.awaiting_approval') }} 
                            <small ng-if="count_awaiting > 0">@{{ count_awaiting }}</small>            
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data="onsale" name="onsale" class="onsale">
                            {{ trans('messages.products.onsale') }} 
                            <small ng-if="count_onsale > 0">@{{ count_onsale }}</small>            
                        </a>
                    </li>
                </ul>
                <div class="check-active">
                    <div class="data-field empty d-flex justify-content-between align-items-center flex-wrap">
                        <div class="col-lg-6 col-md-6 col-12 p-0">
                            <div class="dropdown check product_dropdown">
                                <span class="checkbox" id="no-sel" onclick="$(this).closest('.checkbox').toggleClass('all-sel'); $(this).closest('.check-active').toggleClass('check-active-true');return false;"></span>
                                <a href="#" class="toggle-button" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;"></a>
                                <ul class="check_action">
                                    <li data-status="all"><a href="javascript:void(0);">{{ trans('messages.products.all') }} </a></li>
                                    <li data-status="none"><a href="javascript:void(0);">{{ trans('messages.products.none') }} </a></li>
                                    <li data-status="active"><a href="javascript:void(0);">{{ trans('messages.products.active') }} </a></li>
                                    <li data-status="inactive"><a href="javascript:void(0);">{{ trans('messages.products.inactive') }} </a></li>
                                    <li data-status="soldout"><a href="javascript:void(0);">{{ trans('messages.products.soldout') }} </a></li>
                                    <li data-status="awaiting"><a href="javascript:void(0);">{{ trans('messages.products.awaiting_approval') }} </a></li>
                                    <li data-status="onsale"><a href="javascript:void(0);">{{ trans('messages.products.onsale') }} </a></li>
                                </ul>
                            </div>
                            <div class="dropdown bulk action" style="display: none;">
                                <a href="#" class="toggle-button" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;"><span  class="action_count"></span> {{ trans('messages.products.actions') }}</a>
                                <ul class="check_action">
                                    <li type="bulk"><a href="javascript:void(0);" class="activate-items">{{ trans('messages.products.activate_items') }}</a></li>
                                    <li class="hr"></li>
                                    <li type="bulk"><a href="javascript:void(0);" class="deactivate-items">{{ trans('messages.products.deactivate_items') }}</a></li>
                                    <li class="hr"></li>
                                    <li  type="single"><a href="javascript:void(0);" class="activate-items">{{ trans('messages.products.active_select') }}</a></li>
                                    <li class="hr"></li>
                                    <li  class="active_select" type="single"><a href="javascript:void(0);" class="activate-items">{{ trans('messages.products.active_select') }}</a></li>
                                    <li  class="deactive_select" type="single"><a href="javascript:void(0);" class="deactivate-items">{{ trans('messages.products.deactive_select') }}</a></li>

                                    <li class="hr"></li>
                                    <li type="single"><a href="javascript:void(0);" class="manage-tags">{{ trans('messages.products.manage_tags') }}</a></li>

                                    <li type="single"><a href="javascript:void(0);" class="duplicate-sale-item">{{ trans('messages.products.duplicate_item') }}</a></li>
                                    <li type="single"><a href="javascript:void(0);" class="view-sale-item">{{ trans('messages.products.view_sale') }}</a></li>

                                </ul>
                            </div>
                            <div class="dropdown bulk select-product">
                                <a href="#" class="toggle-button" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;">{{ trans('messages.products.select_products') }}</a>

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 d-flex justify-content-lg-end justify-content-md-end my-3 my-lg-0 my-md-0 align-items-center p-0">
                            <i class="icon-pos icon-search-1 smallsearch" aria-hidden="true"></i>
                            <fieldset class="input-set dropdown" style="border:0px;">
                                <input type="hidden" name="search_by" id="search_by" value="all">
                                <div>
                                 <input name="search" ng-model="search" id="search" class="text head-search" placeholder="{{ trans('messages.products.search') }}" type="text">    
                                <i class="icon-del" ng-click="search = null ; resetfilter()" aria-hidden="true"></i>
                                </div>
                                <div class="dropdown cls_searcgright">
                                    <a class="toggle-button" aria-hidden="true" onclick="$(this).closest('.dropdown').toggleClass('opened');return false;" style="padding-left: 18px;"></a>
                                    
                                    <ul name="search-field" class="search-product-filter droporder check_action">
                                        <li><a href="javascript:void(0);" ng-click="updateFilterby('all')">{{ trans('messages.products.all') }}</a></li>
                                        <li><a href="javascript:void(0);" ng-click="updateFilterby('id')">ID</a></li>
                                        <li><a href="javascript:void(0);" ng-click="updateFilterby('title')">{{ trans('messages.products.product_title') }}</a></li>
                                        <li><a href="javascript:void(0);" ng-click="updateFilterby('sku')">SKU</a></li>
                                    </ul>
                                </div>
                           
                                
                            </fieldset>
                        </div>
                    </div>
                </div>
            
                <div class="all-pro-table tablelist">
                    <div class="table value">
                        <table class="cls_mtable table-trips tableproduct">
                            <colgroup>
                                <col style="*">
                                <col style="width:110px;">
                                <col style="width:110px;">
                                <col style="width:80px;">
                                <col style="width:100px;">
                                <col style="width:100px;">
                                <col style="width:44px;">
                            </colgroup>
                                <thead>
                                <tr class="table-head thlist">
                                    <th><span>{{ trans('messages.products.product') }}</span></th>
                                    <th><span>ID</span></th>

                                    <th><span>{{ trans('messages.products.quantity') }}</span></th>
                                    <th><span>{{ trans('messages.products.sold') }}</span></th>

                                    <th><span>{{ trans('messages.products.status') }}</span></th>
                                    <th style="padding: 0 25px;">{{ trans('messages.products.price') }}</span>
                                    </th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="table-body productdata" ng-repeat="product_item in products.data">
                                     <td class="title productname">
                                        <input data-id="@{{ product_item.id }}" data-thing-id="@{{ product_item.id }}" status="active" type="checkbox" class="pro-check @{{ product_item.status }} @{{ product_item.sold_out }} @{{ product_item.admin_status }}">
                                        <div class="item ">
                                            <a href="{{ url('merchant/edit_product')}}/@{{ product_item.id }}" class="after text-truncate">
                                                <img class="img-responsive-height" ng-src="@{{ product_item.image_name }}" onerror="this.src='{{ $no_product_url }}';">

                                                <b class="title no-option ">@{{ product_item.title }}</b>
                                                <br>
                                                <span class="option">@{{ product_item.option_count }}</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td># @{{ product_item.id }}
                                    </td>
                                    <td class="qty-td">
                                        <div class="qty">
                                            <a href="javascript:;" class="add_qty" style="cursor:text;">@{{ product_item.total_quantity }}</a>
                                            <div class="availability_qty">
                                                <p class="tit">Availability</p>
                                                <ul>
                                                    <li>
                                                        <label>luvy</label> <span class="val">10</span> <span class="range " style="width:50%;"></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                    <td>@{{ product_item.sold }}</td>

                                    <td>
                                        
                                            <span class="all tooltip1 status @{{ product_item.admin_status }}"  ng-if="product_item.sold_out == 'No'">{{ trans('messages.products.Marketplace') }}
                                                <em style="margin-left: -108px;">{{ trans('messages.products.admin_approval') }}</em>
                                            </span>
                                        
                                        
                                            <span class="all tooltip1 sales status @{{ product_item.status }}" ng-if="product_item.sold_out == 'No'">{{ trans('messages.products.Storefront') }}
                                                <em style="margin-left: -108px;">{{ trans('messages.products.visible_storefront') }}</em>
                                            </span>
                                        
                                        
                                            <span class="all tooltip1 retails status soldout @{{ product_item.sold_out }}" ng-if="product_item.sold_out == 'Yes'">{{ trans('messages.products.soldout') }}
                                                <em style="margin-left: -108px;">{{ trans('messages.products.item_sold_out') }}</em>
                                            </span>
                                        
                                    </td>
                                    <td class="price amount"><span ng-bind-html="product_item.products_prices_details.original_currency_symbol"></span> @{{ product_item.products_prices_details.original_price }}
                                        <p style="text-decoration: line-through;" ng-if="product_item.products_prices_details.original_retail_price!=0" class="retails">@{{ product_item.products_prices_details.original_retail_price }}</p>
                                    </td>
                                    <td>
                                        <div class="setting-menu">
                                            <!-- <a href="#" class="btn-setting" onclick="$(this).closest('.setting-menu').toggleClass('opened');return false;"><i class="icon"></i></a> -->
                                            <select class="select_product" data-id="@{{ product_item.id }}" sale-id="2271636" style="width: 100px;">
                                                <option value="none">{{ trans('messages.products.action') }}</option>
                                                <option ng-if="product_item.status=='Active'" value="deactivate">{{ trans('messages.products.deactivate_item') }}</option>
                                                <option ng-if="product_item.status=='Inactive'" value="activate">{{ trans('messages.products.activate_item') }}</option>
                                            </select>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="table-body no_products" style="display: none;text-align: center;padding: 10px;background: #f1f1f1;">
                            {{ trans('messages.products.no_products_found') }}
                        </div>
                    </div>

                    <div class="pb-4">
                        <posts-pagination ng-if="products.total > 10"></posts-pagination>
                    </div>

                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="modal fade" id="active-error" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <p>Some text in the modal.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default js-delete-close" data-toggle="modal" data-target="" data-dismiss="modal">Ok</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    @stop