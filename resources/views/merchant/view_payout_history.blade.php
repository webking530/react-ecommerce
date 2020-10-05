@extends('merchant_template') @section('main')
<div class="cls_dashmain">
    <div class="container container-pad cls_msettings">
         <div class="cls_allproduct pt-4">
    
            <div class="cls_topbread d-flex justify-content-between align-items-center flex-wrap">
                <div>
                  <ol class="breadcrumb">
                        <li class="breadcrumb-item active">{{ trans('messages.header.settings') }}</li>
                      </ol>
                </div>
            </div>

        <div class="csv d-flex flex-wrap p-0" ng-controller="payout_history" ng-cloak>
            <input type="hidden" id="pagin_next" value="{{ trans('messages.pagination.pagi_next') }} ">
            <input type="hidden" id="pagin_prev" value="{{ trans('messages.pagination.pagi_prev') }} "> @include('common.settings_subheader_logged')
            <div class="col-lg-9 col-12 nopad right-sett mt-3 mt-lg-0">
                <div class="cls_setting1">
                <div class="content">
                    <h2 class="csv_tit">{{ trans('messages.merchant.payout_history') }}</h2>

                </div>

                <div class="product-ul">
                    <input type="hidden" id="current" value="Future">
                    <ul class="cls_mtab tab3">
                        <li><a href="javascript:void(0);" data="Future" name="all" class="all current">{{ trans('messages.merchant.transfers') }}</a></li>
                        <li><a href="javascript:void(0);" data="Completed" name="active" class="active ">{{ trans('messages.merchant.balance_history') }}</a></li>
                    </ul>
                </div>
                <div class="all-pro-table p-3">
                    <div class="table value">
                        <table class="cls_mtable tableproduct table-trips ">
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
                                    <th><span>{{ trans('messages.merchant.date') }}</span></th>
                                    <th><span>{{ trans('messages.merchant.type') }}</span></th>
                                    <th><span>{{ trans('messages.merchant.details') }}</span></th>
                                    <th><span>{{ trans('messages.merchant.amount') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-body" ng-repeat="transfer_item in transfers.data" ng-show="transfer_item.amount > 0">
                                    <td>@{{ transfer_item.updated_at }}</td>
                                    <td>{{ trans('messages.merchant.payout') }}</td>
                                    <td>{{ trans('messages.merchant.payout_admin') }}</td>
                                    <td>{{ session::get('symbol')}} @{{ transfer_item.amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class=" no_results" style="display: none;text-align: center;padding: 10px;background: #f1f1f1;">
                            {{ trans('messages.merchant.no_transfers_found') }}
                        </div>
                    </div>

                    <div class="pb-4">
                        <transfers-pagination ng-if="transfers.total > 10"></transfers-pagination>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    </div>
</div>
@stop