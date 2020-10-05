<?php

/**
 * Orders DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    Orders
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Orders;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
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
            ->addColumn('buyer_name', function($orders){
                return $orders->buyer_name;
            })
            ->addColumn('product_count', function($orders){
                return $orders->orders_details->count();
            })
            ->addColumn('currency', function($orders){
                return $orders->currency->session_code;
            })
            ->addColumn('action', function ($orders) {
                 return '<a href="'.url(ADMIN_URL.'/view_order/'.$orders->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i></a>';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Orders $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Orders $model)
    {
        return $model->with(['orders_details'])->get();
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
                    ->addAction(["printable" => false])
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
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'buyer_name', 'name' => 'buyer_name', 'title' => 'Buyer Name'],
            ['data' => 'product_count', 'name' => 'product_count', 'title' => 'Product Count','searchable' => false],
            ['data' => 'service_fee', 'name' => 'service_fee', 'title' => 'Service Fee'],
            ['data' => 'shipping_fee', 'name' => 'shipping_fee', 'title' => 'Product Fee'],
            ['data' => 'incremental_fee', 'name' => 'incremental_fee', 'title' => 'Incremental Fee'],
            ['data' => 'merchant_fee', 'name' => 'merchant_fee', 'title' => 'Merchant Fee'],
            ['data' => 'subtotal', 'name' => 'subtotal', 'title' => 'Subtotal'],
            ['data' => 'total', 'name' => 'total', 'title' => 'Total'],
            ['data' => 'currency', 'name' => 'currency', 'title' => 'Currency'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'orders_' . date('YmdHis');
    }
}