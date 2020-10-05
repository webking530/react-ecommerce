<?php

/**
 * CouponCode DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    CouponCode
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\CouponCode;
use Yajra\DataTables\Services\DataTable;

class CouponCodeDataTable extends DataTable
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
            ->addColumn('action', function ($coupon_code) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_coupon_code/'.$coupon_code->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                $delete = '<a data-href="'.url(ADMIN_URL.'/delete_coupon_code/'.$coupon_code->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
              
                return $edit.'&nbsp;'.$delete;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \CouponCode $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CouponCode $model)
    {
        return $model->orderBy('id','DESC');
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
        return ['id', 'coupon_code', 'amount', 'currency_code', 'expired_at','status'];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'coupon_code_' . date('YmdHis');
    }
}