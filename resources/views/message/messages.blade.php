@extends('template')
@section('main')

<main id="site-content" role="main" class="msg_new">
    <div class="container">
        <div class="" ng-controller="messages" ng-cloak>

            <div class="row cls_messageall message_block" id="main-content-id" ng-init="user_from={{ Auth::id() }}">

                <div class="col-lg-3 col-12 cls_sideleft  back-white nopad">
                    <ul class="sort-msg">
                        <li class="inbox">
                            <span class="threads" type="friend"><b>{{ trans('messages.inbox.inbox') }}</b></span>
                        </li>
                        <li>
                        	<a href="javascript:;" ng-click="clear_results()" class="btns-gray-embo  msg_btn">{{ trans('messages.inbox.new_message') }}</a>
                        </li>
                    </ul>

                    <div class="msg-content-mail">
                        <fieldset class="input-set">
                            <input class="head-search form-control" id="side_search" placeholder="{{ trans('messages.inbox.search_message') }}" my-enter="search_sidebar()" ng-model="messageSearch" type="text">
                            <i class="icon-pos icon-search-1" aria-hidden="true"></i></fieldset>
                        <div id="message_side_loading" class="whiteloading" style="display:none"></div>
                        <div id="message_side_empty" style="display:none"><i class="fa fa-inbox"></i>
                            <p>{{ trans('messages.inbox.message_empty') }}</p>
                        </div>
                        <ul class="msg-list">
                            <li ng-repeat="side_data in message_sidebar" ng-click="show_user_message(side_data.group_id,side_data.user_to.id,side_data.user_from.id,side_data.user_to.full_name,side_data.user_from.full_name)" class="d-flex align-items-top border-bottom py-2">
                            	<div class="col-lg-2 p-0 text-center">
	                                <img ng-if="side_data.user_from.id!=user_from" ng-src="@{{ side_data.user_from.original_image_name }}" class="img-round" width="30px" height="30px">
	                                <img ng-if="side_data.user_from.id==user_from" ng-src="@{{ side_data.user_to.original_image_name }}" class="img-round" width="30px" height="30px">
                            	</div>
                                <div class="msg-body col-lg-7 p-0">
                                    <span ng-if="side_data.user_to.id!=user_from" class="text-truncate">@{{ side_data.user_to.full_name }}</span>
                                    <span ng-if="side_data.user_from.id!=user_from" class="text-truncate">@{{ side_data.user_from.full_name }}</span>
                                    <p class="last_message text-truncate">@{{ side_data.message }} </p>
                                </div>
                                <div class="date col-lg-3 p-0">@{{ side_data.created_time }}</div>

                            </li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 col-12 cls_sideright p-0 border-left title-msg ">
                    <div class="new-msg-title">
                        <div class="message_user border-bottom">
                            <label class="flt-left">{{ trans('messages.inbox.to') }} :</label>
                            <input type="text" ng-keyup="load_users()" data-id="" data-group-id="" placeholder="{{ trans('messages.inbox.select_user') }} " id="select_user" class="text cls_input">
                           <!--  <a href="javascript:;" ng-click="clear_results()" class="btns-gray-embo  msg_btn">{{ trans('messages.inbox.new_message') }}</a> -->
                        </div>
                        <div id="show_user" style="display:none">
                            <div id="user_loading" class="whiteloading" style="display:none"></div>
                            <div id="user_empty" style="display:none">{{ trans('messages.inbox.user_empty') }}<i class="fa fa-times" ng-click="clear_results()" style="float:right;padding:0 10px"></i></div>
                            <ul>
                                <li ng-repeat="users in users_list" ng-click="select_user(users.full_name,users.id,users.group_id)">
                                    @{{ users.full_name }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="message-filed">
                        <div id="message_loading" class="whiteloading" style="display:none"></div>
                        <ul class="message_detail" id="message_detail">
                            <li ng-repeat="messages in all_messages" class="d-flex my-4" ng-class="(messages.user_from.id==user_from) ? 'justify-content-end text-right cls_right' : 'justify-content-start text-left cls_left'">
                                <div class="col-lg-2 col-3 user_image  text-right" ng-if="messages.user_from.id!=user_from">
                                    <img height="40px" width="40px" ng-src="@{{ messages.user_from.original_image_name }}">
                                </div>

                                <div class="col-lg-7 col-9 message_string">
                                    <span>@{{ messages.message }}</span>
                                    <div class="ctime_to" ng-if="messages.user_from.id!=user_from">@{{ messages.created_time }}</div>
                                    <div class="ctime_from" ng-if="messages.user_from.id==user_from">@{{ messages.created_time }}</div>
                                </div>

                                <div class="col-lg-2 col-3 text-left user_image" ng-if="messages.user_from.id==user_from">
                                    <img height="50px" width="50px" ng-src="@{{ messages.user_from.original_image_name }}">
                                </div>

                            </li>
                        </ul>
                    </div>
                    <input type="hidden" id="user_from" value="{{ Auth::id() }}">
                    <div class="frm send_message flex-wrap d-flex align-items-center p-3 after disabled">
                        <div class="text_box col-lg-8 col-12">
                            <textarea class="text form-control" placeholder="{{ trans('messages.inbox.type_message') }}" id="message_text"></textarea>
                        </div>
                        <div class="col-lg-4 col-12 text-center py-3 py-lg-0">
	                        <button class="btns-blue-embo btn-refresh" id="refresh_message" ng-click="refresh_message()" >{{ trans('messages.inbox.refresh') }}</button>

	                        <span class="auto-message">
	                        	<button class="btns-blue-embo btn-send" id="send_message" ng-click="send_message()" disabled="">{{ trans('messages.inbox.send_message') }}</button></span>
	                        <span class="byte"></span>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection