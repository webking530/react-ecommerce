<?php

/**
 * Reports Controller
 *
 * @package     Spiffy
 * @subpackage  Controller
 * @category    Reports
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Start\Helpers;
use App\Models\User;
use App\Models\Product;
use App\Models\Orders;
use Validator;
use DB;
use App\Exports\ArrayExport;

class ReportsController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Reports
     *
     * @return view file
     */
    public function index(Request $request)
    {
        if($request->isMethod('get')) {
            return view('admin.reports');
        }

        $from = date('Y-m-d H:i:s', strtotime($request->from));
        $to = date('Y-m-d H:i:s',strtotime($request->to." 23:59:59"));
        $category = $request->category;

        if($category == '') {               
            $result = User::where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();
        }
         if($category == 'merchant') {
            $result = User::where('created_at', '>=', $from)->where('created_at', '<=', $to)->where('type','merchant')->get();
        }
        if($category == 'items') {
            $result = Product::where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();
        }
        if($category == 'orders') {
            $result = Orders::where('created_at', '>=', $from)->where('created_at', '<=', $to)-> join('currency', function($join) {
                $join->on('currency.code', '=', 'orders.currency_code');
            })
            ->select('orders.id as id','orders.buyer_id as buyer_id','paymode','orders.created_at as created_at', DB::raw('CONCAT(currency.symbol, orders.total) AS total_amount'))->get();
        }

        $final['from']=$request->from == '' ? 'all' : date('Y-m-d',strtotime($request->from));
        $final['to']=$request->to == '' ? 'all' : date('Y-m-d',strtotime($request->to));
        $final['result']=$result;

        return $final;
    }

    public function export(Request $request)
    {  
        $from = $request->from == 'all' ? '' : date('Y-m-d H:i:s', strtotime($request->from));
        $to = $request->to == 'all' ? '' : date('Y-m-d H:i:s', strtotime($request->to." 23:59:59"));

        if($request->category == 'users') {
            if($from != '' && $to =='')
                $results =  User::where('created_at', '>=', $from);
            if($to != '' && $from == '')
                $results =  User::where('created_at', '<=', $to);
            if($from !='' && $to !='')
                $results =  User::where('created_at', '>=', $from)->where('created_at', '<=', $to);

            $results = $results->get()->toArray();

            foreach($results as $i => $res){
                $result[$i]['Id'] = $res['id'];
                $result[$i]['Full Name'] = $res['full_name'];
                $result[$i]['User Name'] = $res['user_name'];
                $result[$i]['email'] = $res['email'];
                $result[$i]['Store Name'] = $res['store_name'];
                $result[$i]['Store Name'] = $res['store_name'];
                $result[$i]['User Type'] = ucfirst($res['type']);
                $result[$i]['Status'] = $res['status'];
                $result[$i]['Registered At'] = $res['created_at'];
            }
        }
        if($request->category == 'merchant') {
            $results = User::where('type','merchant');

            if($from != '')
                $results =  $results->where('created_at', '>=', $from);
            if($to != '')
                 $results =  $results->where('created_at', '<=', $to);
            
            $results = $results->get();

            foreach($results as $i => $res){
                $result[$i]['Id'] = $res['id'];
                $result[$i]['Full Name'] = $res['full_name'];
                $result[$i]['User Name'] = $res['user_name'];
                $result[$i]['email'] = $res['email'];
                $result[$i]['Store Name'] = $res['store_name'];
                $result[$i]['Store Name'] = $res['store_name'];
                $result[$i]['User Type'] = ucfirst($res['type']);
                $result[$i]['Status'] = $res['status'];
                $result[$i]['Registered At'] = $res['created_at'];
            }
        }
        if($request->category == 'items') {
            if($from != '' && $to =='')
                $results =  Product::where('created_at', '>=', $from);
            if($to != '' && $from == '')
                 $results =  Product::where('created_at', '<=', $to);
            if($from !='' && $to !='')
                $results =  Product::where('created_at', '>=', $from)->where('created_at', '<=', $to);
            
            $results = $results->get();

            foreach($results as $i => $res){
                $result[$i]['Id'] = $res['id'];
                $result[$i]['User Name'] = $res['user_name'];
                $result[$i]['Title'] = $res['title'];
                $result[$i]['Category Name'] = $res['category_name'];
                $result[$i]['Store Name'] = $res['store_name'];
                $result[$i]['Price'] = html_entity_decode(@$res['currency_symbol']).@$res['price'];;
                $result[$i]['Total Quantity'] = $res['total_quantity'];
                $result[$i]['Soldout'] = $res['sold'];
                $result[$i]['Shipping Type'] = $res['shipping_type'];
                $result[$i]['Status'] = $res['status'];
                $result[$i]['Admin Status'] = $res['admin_status'];
                $result[$i]['Created At'] = $res['created_at'];
            }
        }
        if($request->category == 'orders') {
            $results = Orders::join('currency', function($join) {
                    $join->on('currency.code', '=', 'orders.currency_code');
                })
                ->select('orders.id as id','orders.buyer_id as buyer_id','paymode','orders.created_at as created_at', DB::raw('CONCAT(currency.symbol, orders.total) AS total_amount'));

            if($from != '') {
                $results =  $results->where('created_at', '>=', $from);
            }
            if($to != '') {
                $results =  $results->where('created_at', '<=', $to);
            }
            
            $results = $results->get()->toArray();

            foreach($results as $i => $res){
                $result[$i]['Id'] = $res['id'];
                $result[$i]['Buyer Id'] = $res['buyer_id'];
                $result[$i]['Buyer Name'] = $res['buyer_name'];
                $result[$i]['Payment Mode'] = $res['show_payment_mode'];
                $result[$i]['Total Amount'] = html_entity_decode(@$res['total_amount'], ENT_COMPAT, 'UTF-8');
                $result[$i]['Order Date'] = $res['order_date'];
                $result[$i]['Created At'] = $res['created_at'];
            }
        }

        if(count($result) == 0) {
            return '';
        }

        return \Excel::download(new ArrayExport($result),$request->category . '-report.csv');
    }
}