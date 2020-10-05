<?php

/**
 * Owe DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    Owe
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\OrdersDetails;
use Yajra\DataTables\Services\DataTable;

class OweDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->of($query)
            ->addColumn('order_ids', function ($owe) {
                $det=OrdersDetails::where('merchant_id',$owe->id)->where('owe_amount','>',0)->pluck('order_id')->toArray();
                $det_apply=OrdersDetails::where('merchant_id',$owe->id)->where('applied_owe_amount','>',0)->pluck('order_id')->toArray();
                return '<div class="min_width">'.implode(',',array_unique(array_merge($det,$det_apply))).'</div>';
            })
            ->addColumn('currency_code', function ($owe) {
                if(Session::get('currency'))
                   return Session::get('currency');
                else
                   return DB::table('currency')->where('default_currency', 1)->first()->code;
            })
            ->addColumn('owe_amount_show', function ($owe) {
                $owe_a=OrdersDetails::where('merchant_id',$owe->id);
                $owe_show=0;
                if($owe_a->count())
                {
                    foreach ($owe_a->get() as $owe_value) {
                        $owe_show+=@$owe_value->calc_owe_amount;
                    }
                }
                return $owe_show;
            })
            ->addColumn('applied_owe_amount_show', function ($owe) {
                $owe_a=OrdersDetails::where('merchant_id',$owe->id);
                $owe_show=0;
                if($owe_a->count())
                {
                    foreach ($owe_a->get() as $owe_value) {
                        $owe_show+=@$owe_value->calc_a_owe_amount;
                    }
                }
                return $owe_show;
            })
            ->addColumn('remaining_owe_amount_show', function ($owe) {
                $owe_a=OrdersDetails::where('merchant_id',$owe->id);
                $owe_show=0;
                if($owe_a->count())
                {
                    foreach ($owe_a->get() as $owe_value) {
                        $owe_show+=@$owe_value->calc_r_owe_amount;
                    }
                }
                return $owe_show;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \OrdersDetails $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(OrdersDetails $model)
    {
        return $model->join('users', function($join) {
                $join->on('users.id', '=', 'orders_details.merchant_id');
            })
            ->select('orders_details.id as orders_id','orders_details.order_id as order_id','users.id as id', 'users.full_name', 'users.email',DB::raw('SUM(orders_details.owe_amount) as total_owe_amount'),DB::raw('SUM(orders_details.remaining_owe_amount) as total_remaining_owe_amount'),DB::raw('SUM(orders_details.applied_owe_amount) as total_applied_owe_amount'))
            ->having('total_owe_amount','>',0)
            ->groupBy('orders_details.merchant_id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('lBfr<"table-responsive"t>ip')
                    ->orderBy(0)
                    ->buttons(
                        ['csv','excel', 'print', 'reset']
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return array(
            ['data' => 'id', 'name' => 'users.id', 'title' => 'Merchant Id'],
            ['data' => 'full_name', 'name' => 'users.full_name', 'title' => 'First Name'],
            ['data' => 'order_ids', 'name' => 'order_ids', 'title' => 'Order Ids','orderable' => false, 'searchable' => false],
            ['data' => 'owe_amount_show', 'name' => 'owe_amount_show', 'title' => 'Owe Amount','orderable' => false, 'searchable' => false],
            ['data' => 'applied_owe_amount_show', 'name' => 'applied_owe_amount_show', 'title' => 'Applied Owe Amount','orderable' => false, 'searchable' => false],
            ['data' => 'remaining_owe_amount_show', 'name' => 'remaining_owe_amount_show', 'title' => 'Remaining Owe Amount','orderable' => false, 'searchable' => false],
            ['data' => 'currency_code', 'name' => 'currency_code', 'title' => 'Currency Code','orderable' => false, 'searchable' => false],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'owe_' . date('YmdHis');
    }
}