<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as BaseController;
use App\Models\OrdersDetails;
use App\Models\Orders;
use App\Models\User;
use App\Models\Messages;
use App\Http\Start\Helpers;
use Session;
use DB;
use App\Models\Category;

class MessageController extends BaseController
{
    public function index(Request $request)
    {
        $data['page']='browse';
        $data['categories'] = Category::where("parent_id",0)->where('status','Active')->get();
    	return view('message.messages',$data);
    }

    public function get_messages(Request $request)
    {
        $update_data['read']='1';
        Messages::where('group_id',$request->group_id)->where('user_to',Auth::id())->update($update_data);
        $messages=Messages::with([
            'user_from' => function($query) {},
            'user_to' => function($query) {}
            ])->where('messages.group_id',$request->group_id)->get()->tojson();

        $count['count_unread']=Messages::where('group_id',$request->group_id)->where('user_to',Auth::id())->where('read','0')->count();

        $messages = json_decode($messages, true);
        $final=array_merge($messages, array('count'=>$count));
        echo json_encode($final);
    }

    public function get_unread(Request $request)
    {
        $update_data['read']='1';
        Messages::where('group_id',$request->group_id)->where('user_to',Auth::id())->update($update_data);
        return $messages=Messages::with([
            'user_from' => function($query) {},
            'user_to' => function($query) {}
            ])->where('messages.group_id',$request->group_id)->get()->tojson();
    }

    public function get_sidebar_user(Request $request)
    {
        $user_id = Auth::id();
        $search=$request->searchfor;
        $result =   Messages::whereIn('id', function($query) use($user_id,$search)
                    {   
                        $query->select(DB::raw('max(id)'))->from('messages')->groupby('group_id')->orderBy('id','desc');
                        if(trim($search)!="")
                        {
                            $query->where('message','like','%'.$search.'%');
                        }
                        $query->where(function ($query1) use($user_id){
                            return $query1->where('user_to', $user_id)->orwhere('user_from', $user_id); 
                        });
                    })->with(['user_from' => function($query) {
                        $query->with('profile_picture');
                    }])->with(['user_to' => function($query) {
                        $query->with('profile_picture');
                    }])->orderBy('id','desc');
        echo $result->get()->tojson();
    }

    public function get_message_user(Request $request)
    {
    	$merchant_users=array();
    	$buyer_users=array();
    	$merchants=Orders::with([
                'orders_details'  =>function($query){},
            ])->where('buyer_id',Auth::id());
    
     	$buyers=OrdersDetails::with([
                'orders'  =>function($query){},
            ])->where('merchant_id',Auth::id());

    	if($merchants->count())
    	{
	    	foreach($merchants->get() as $row_merchant) 
	    	{
	    		foreach($row_merchant->orders_details as $row_merchant_orders)
	    		{
		    		$merchant_users[]=$row_merchant_orders->merchant_id;	
	    		}
	    		
	    	}
    	}
    	
    	if($buyers->count())
    	{
	    	foreach($buyers->get() as $row_buyer) 
	    	{
		    	$buyer_users[]=$row_buyer->orders->buyer_id;	
	    	}
    	}
    	$final_users=array_merge($merchant_users,$buyer_users);
        $final_users = array_diff($final_users, [Auth::id()]);
        $final_users=array_unique($final_users);
        $user=User::wherein('id',$final_users);
        if($request->text!="")
        {
            $user->where('full_name', 'like', '%'.$request->text.'%');
        }
    	$user=$user->get()->tojson();
    	return json_decode($user);
    }

    public function send_messages(Request $request)
    {
        $user_from=Auth::id();
        $get_group_message_user_from=Messages::where('user_to',$request->user_to)->where('user_from',$user_from);
        $get_group_message_user_to=Messages::where('user_to',$user_from)->where('user_from',$request->user_to);
        if($get_group_message_user_from->count())
        {
            $group_id=$get_group_message_user_from->first()->group_id;
        }
        elseif($get_group_message_user_to->count())
        {
            $group_id=$get_group_message_user_to->first()->group_id;   
        }
        else
        {
            if(Messages::count())
            {
                $max_group_id=Messages::orderBy('group_id','desc')->first()->group_id;
                $group_id= $max_group_id+1;
            }
            else
            {
                $group_id= "1000";    
            }
        }
        

        $message_data['user_from']=$user_from;
        $message_data['user_to']=$request->user_to;
        $message_data['message']=$request->message;
        $message_data['group_id']=$group_id;
        $message_data['read']='0';
        $message_data['created_at'] = date('Y-m-d H:i:s');
        $message_data['updated_at'] =  date('Y-m-d H:i:s');
        Messages::insert($message_data);

        return $messages=Messages::with([
            'user_from' => function($query) {},
            'user_to' => function($query) {}
            ])->where('messages.group_id',$group_id)->orderBy('id','desc')->first()->tojson();
    }
}
